<?php
/**
 * Stats.php
 * ---------
 * Implementasi murni PHP (tanpa library eksternal) untuk:
 *   - Statistik deskriptif
 *   - Uji normalitas Shapiro-Wilk (algoritma Royston 1995, AS R94)
 *   - Paired Samples T-Test
 *   - Wilcoxon Signed Rank Test (aproksimasi normal, sama seperti metode
 *     "Asymptotic" default SPSS untuk 2 Related Samples)
 *
 * PENTING: Shapiro-Wilk di sini pakai APROKSIMASI (bukan tabel eksak SPSS),
 * paling akurat untuk n >= 12. Selalu silangkan hasilnya dengan output SPSS
 * aslimu sebagai validasi akhir sebelum dipakai di skripsi.
 */

class Stats
{
    // ---------- Statistik dasar ----------

    public static function mean(array $x): float
    {
        return array_sum($x) / count($x);
    }

    public static function variance(array $x): float
    {
        $n = count($x);
        $m = self::mean($x);
        $sum = 0.0;
        foreach ($x as $v) {
            $sum += ($v - $m) ** 2;
        }
        return $sum / ($n - 1); // sample variance
    }

    public static function stddev(array $x): float
    {
        return sqrt(self::variance($x));
    }

    public static function median(array $x): float
    {
        $sorted = $x;
        sort($sorted);
        $n = count($sorted);
        $mid = intdiv($n, 2);
        if ($n % 2 === 0) {
            return ($sorted[$mid - 1] + $sorted[$mid]) / 2;
        }
        return $sorted[$mid];
    }

    // ---------- Fungsi distribusi normal ----------

    /** Fungsi error erf(x), aproksimasi Abramowitz & Stegun 7.1.26 */
    public static function erf(float $x): float
    {
        $sign = $x < 0 ? -1 : 1;
        $x = abs($x);

        $a1 = 0.254829592;
        $a2 = -0.284496736;
        $a3 = 1.421413741;
        $a4 = -1.453152027;
        $a5 = 1.061405429;
        $p = 0.3275911;

        $t = 1.0 / (1.0 + $p * $x);
        $y = 1.0 - ((((($a5 * $t + $a4) * $t) + $a3) * $t + $a2) * $t + $a1) * $t * exp(-$x * $x);

        return $sign * $y;
    }

    /** CDF normal standar Φ(z) */
    public static function normalCDF(float $z): float
    {
        return 0.5 * (1.0 + self::erf($z / sqrt(2)));
    }

    /** Inverse CDF normal standar (fungsi qnorm), aproksimasi Beasley-Springer-Moro */
    public static function qnorm(float $p): float
    {
        // Rational approximation (Acklam's algorithm)
        $a = [-3.969683028665376e+01, 2.209460984245205e+02, -2.759285104469687e+02,
              1.383577518672690e+02, -3.066479806614716e+01, 2.506628277459239e+00];
        $b = [-5.447609879822406e+01, 1.615858368580409e+02, -1.556989798598866e+02,
              6.680131188771972e+01, -1.328068155288572e+01];
        $c = [-7.784894002430293e-03, -3.223964580411365e-01, -2.400758277161838e+00,
              -2.549732539343734e+00, 4.374664141464968e+00, 2.938163982698783e+00];
        $d = [7.784695709041462e-03, 3.224671290700398e-01, 2.445134137142996e+00,
              3.754408661907416e+00];

        $plow = 0.02425;
        $phigh = 1 - $plow;

        if ($p < $plow) {
            $q = sqrt(-2 * log($p));
            return ((((($c[0]*$q+$c[1])*$q+$c[2])*$q+$c[3])*$q+$c[4])*$q+$c[5]) /
                   (((($d[0]*$q+$d[1])*$q+$d[2])*$q+$d[3])*$q+1);
        } elseif ($p <= $phigh) {
            $q = $p - 0.5;
            $r = $q * $q;
            return ((((($a[0]*$r+$a[1])*$r+$a[2])*$r+$a[3])*$r+$a[4])*$r+$a[5])*$q /
                   ((((($b[0]*$r+$b[1])*$r+$b[2])*$r+$b[3])*$r+$b[4])*$r+1);
        } else {
            $q = sqrt(-2 * log(1 - $p));
            return -((((($c[0]*$q+$c[1])*$q+$c[2])*$q+$c[3])*$q+$c[4])*$q+$c[5]) /
                    (((($d[0]*$q+$d[1])*$q+$d[2])*$q+$d[3])*$q+1);
        }
    }

    // ---------- Incomplete beta function (untuk t-distribution CDF) ----------

    private static function betacf(float $x, float $a, float $b): float
    {
        $MAXIT = 200;
        $EPS = 3.0e-9;
        $FPMIN = 1.0e-30;

        $qab = $a + $b;
        $qap = $a + 1.0;
        $qam = $a - 1.0;
        $c = 1.0;
        $d = 1.0 - $qab * $x / $qap;
        if (abs($d) < $FPMIN) $d = $FPMIN;
        $d = 1.0 / $d;
        $h = $d;

        for ($m = 1; $m <= $MAXIT; $m++) {
            $m2 = 2 * $m;
            $aa = $m * ($b - $m) * $x / (($qam + $m2) * ($a + $m2));
            $d = 1.0 + $aa * $d;
            if (abs($d) < $FPMIN) $d = $FPMIN;
            $c = 1.0 + $aa / $c;
            if (abs($c) < $FPMIN) $c = $FPMIN;
            $d = 1.0 / $d;
            $h *= $d * $c;
            $aa = -($a + $m) * ($qab + $m) * $x / (($a + $m2) * ($qap + $m2));
            $d = 1.0 + $aa * $d;
            if (abs($d) < $FPMIN) $d = $FPMIN;
            $c = 1.0 + $aa / $c;
            if (abs($c) < $FPMIN) $c = $FPMIN;
            $d = 1.0 / $d;
            $del = $d * $c;
            $h *= $del;
            if (abs($del - 1.0) < $EPS) break;
        }
        return $h;
    }

    private static function betai(float $x, float $a, float $b): float
    {
        if ($x <= 0.0) return 0.0;
        if ($x >= 1.0) return 1.0;

        $bt = exp(
            self::gammaLn($a + $b) - self::gammaLn($a) - self::gammaLn($b) +
            $a * log($x) + $b * log(1.0 - $x)
        );

        if ($x < ($a + 1.0) / ($a + $b + 2.0)) {
            return $bt * self::betacf($x, $a, $b) / $a;
        } else {
            return 1.0 - $bt * self::betacf(1.0 - $x, $b, $a) / $b;
        }
    }

    private static function gammaLn(float $xx): float
    {
        $cof = [76.18009172947146, -86.50532032941677, 24.01409824083091,
                -1.231739572450155, 0.1208650973866179e-2, -0.5395239384953e-5];
        $x = $xx;
        $y = $xx;
        $tmp = $x + 5.5;
        $tmp -= ($x + 0.5) * log($tmp);
        $ser = 1.000000000190015;
        for ($j = 0; $j <= 5; $j++) {
            $y += 1;
            $ser += $cof[$j] / $y;
        }
        return -$tmp + log(2.5066282746310005 * $ser / $x);
    }

    /** CDF distribusi t Student, dua sisi -> mengembalikan P(T <= t) */
    public static function tCDF(float $t, float $df): float
    {
        $x = $df / ($df + $t * $t);
        $p = self::betai($x, $df / 2.0, 0.5) / 2.0;
        return $t > 0 ? 1.0 - $p : $p;
    }

    /** p-value dua sisi dari statistik t */
    public static function tTestPValue(float $t, float $df): float
    {
        $p_one = 1.0 - self::tCDF(abs($t), $df);
        return 2.0 * $p_one;
    }

    // ---------- Shapiro-Wilk (Royston 1995 / AS R94), untuk n >= 12 ----------

    public static function shapiroWilk(array $x): array
    {
        $n = count($x);
        if ($n < 12) {
            return [
                'W' => null, 'p' => null,
                'note' => 'n < 12: aproksimasi Royston di tool ini kurang akurat untuk sampel sekecil ini. Gunakan SPSS untuk hasil pasti.',
            ];
        }

        $sorted = $x;
        sort($sorted);

        // Blom's approximation untuk expected order statistics
        $m = [];
        for ($i = 1; $i <= $n; $i++) {
            $m[$i] = self::qnorm(($i - 0.375) / ($n + 0.25));
        }
        $ssumm2 = 0.0;
        foreach ($m as $mi) $ssumm2 += $mi * $mi;

        $rsn = 1 / sqrt($n);

        $a_n = -2.706056 * $rsn**5 + 4.434685 * $rsn**4 - 2.071190 * $rsn**3
             - 0.147981 * $rsn**2 + 0.221157 * $rsn + $m[$n] / sqrt($ssumm2);
        $a_n1 = -3.582633 * $rsn**5 + 5.682633 * $rsn**4 - 1.752461 * $rsn**3
              - 0.293762 * $rsn**2 + 0.042981 * $rsn + $m[$n - 1] / sqrt($ssumm2);

        $fac = sqrt(($ssumm2 - 2 * $m[$n]**2 - 2 * $m[$n - 1]**2) / (1 - 2 * $a_n**2 - 2 * $a_n1**2));

        $a = [];
        $a[$n] = $a_n;
        $a[1] = -$a_n;
        $a[$n - 1] = $a_n1;
        $a[2] = -$a_n1;
        for ($i = 3; $i <= $n - 2; $i++) {
            $a[$i] = $m[$i] / $fac;
        }

        $xbar = self::mean($sorted);
        $b = 0.0;
        for ($i = 1; $i <= $n; $i++) {
            $b += $a[$i] * $sorted[$i - 1];
        }
        $ss = 0.0;
        foreach ($sorted as $v) $ss += ($v - $xbar) ** 2;

        $W = ($b * $b) / $ss;
        $W = max(0.0, min(1.0, $W)); // guard pembulatan

        // Transformasi W -> p-value (Royston 1995), valid untuk 12 <= n <= 2000
        $lnN = log($n);
        $mu = -1.5861 - 0.31082 * $lnN - 0.083751 * $lnN**2 + 0.0038915 * $lnN**3;
        $sigma = exp(-0.4803 - 0.082676 * $lnN + 0.0030302 * $lnN**2);
        $y = log(1 - $W);
        $z = ($y - $mu) / $sigma;
        $p = 1 - self::normalCDF($z);

        return ['W' => $W, 'p' => $p, 'note' => null];
    }

    // ---------- Paired Samples T-Test ----------

    public static function pairedTTest(array $x1, array $x2): array
    {
        $n = count($x1);
        $diff = [];
        for ($i = 0; $i < $n; $i++) {
            $diff[] = $x1[$i] - $x2[$i];
        }
        $meanDiff = self::mean($diff);
        $sdDiff = self::stddev($diff);
        $seDiff = $sdDiff / sqrt($n);
        $t = $meanDiff / $seDiff;
        $df = $n - 1;
        $p = self::tTestPValue($t, $df);
        $cohenD = $meanDiff / $sdDiff;

        return [
            'n' => $n,
            'mean_x1' => self::mean($x1),
            'mean_x2' => self::mean($x2),
            'mean_diff' => $meanDiff,
            'sd_diff' => $sdDiff,
            'se_diff' => $seDiff,
            'df' => $df,
            't' => $t,
            'p' => $p,
            'cohen_d' => $cohenD,
            'diff' => $diff,
        ];
    }

    // ---------- Wilcoxon Signed Rank Test (aproksimasi normal / asymptotic) ----------

    public static function wilcoxonSignedRank(array $x1, array $x2): array
    {
        $n0 = count($x1);
        $diffs = [];
        for ($i = 0; $i < $n0; $i++) {
            $d = $x1[$i] - $x2[$i];
            if ($d != 0) $diffs[] = $d; // pasangan dengan selisih 0 dibuang (standar Wilcoxon)
        }
        $n = count($diffs);

        $absDiffs = array_map('abs', $diffs);
        $order = range(0, $n - 1);
        array_multisort($absDiffs, $order);

        // hitung rank dengan penanganan ties (rata-rata rank)
        $sortedAbs = array_values($absDiffs);
        $ranks = array_fill(0, $n, 0.0);
        $i = 0;
        while ($i < $n) {
            $j = $i;
            while ($j < $n - 1 && $sortedAbs[$j + 1] == $sortedAbs[$i]) $j++;
            $avgRank = (($i + 1) + ($j + 1)) / 2.0;
            for ($k = $i; $k <= $j; $k++) $ranks[$k] = $avgRank;
            $i = $j + 1;
        }

        $Wpos = 0.0;
        $Wneg = 0.0;
        for ($k = 0; $k < $n; $k++) {
            $origIndex = $order[$k];
            if ($diffs[$origIndex] > 0) {
                $Wpos += $ranks[$k];
            } else {
                $Wneg += $ranks[$k];
            }
        }

        $Wstat = min($Wpos, $Wneg);

        // Normal approximation dengan continuity correction (metode Asymptotic SPSS)
        $meanW = $n * ($n + 1) / 4.0;
        $sdW = sqrt($n * ($n + 1) * (2 * $n + 1) / 24.0);

        // koreksi ties
        $tieGroups = [];
        $i = 0;
        while ($i < $n) {
            $j = $i;
            while ($j < $n - 1 && $sortedAbs[$j + 1] == $sortedAbs[$i]) $j++;
            $tieGroups[] = $j - $i + 1;
            $i = $j + 1;
        }
        $tieCorrection = 0.0;
        foreach ($tieGroups as $t) {
            $tieCorrection += ($t ** 3 - $t);
        }
        $sdW = sqrt(($n * ($n + 1) * (2 * $n + 1) - $tieCorrection / 2) / 24.0);

        $z = ($Wpos - $meanW) / $sdW;
        // continuity correction
        if ($z > 0) $z -= 0.5 / $sdW; else $z += 0.5 / $sdW;

        $p = 2 * (1 - self::normalCDF(abs($z)));

        return [
            'n' => $n,
            'median_x1' => self::median($x1),
            'median_x2' => self::median($x2),
            'W_stat' => $Wstat,
            'z' => $z,
            'p' => $p,
        ];
    }
}