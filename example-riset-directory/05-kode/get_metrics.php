<?php


function getSystemMetrics() {
    // 1. Membaca CPU
    $cpu_cmd = shell_exec('powershell -command "[math]::Round((Get-Counter -Counter \'\\Processor(_Total)\\% Processor Time\' -SampleInterval 1).CounterSamples.CookedValue)"');
    $cpu_load = trim($cpu_cmd);
    if (!is_numeric($cpu_load)) { $cpu_load = "0"; }

    // 2. Membaca Beban Disk (Penyimpanan)
    $disk_cmd = shell_exec('powershell -command "[math]::Round((Get-Counter -Counter \'\\PhysicalDisk(_Total)\\% Disk Time\' -SampleInterval 1).CounterSamples.CookedValue)"');
    $disk_load = trim($disk_cmd);
    if (!is_numeric($disk_load)) { $disk_load = "0"; }
    if ($disk_load > 100) { $disk_load = "100"; } // Batasi maksimal 100%

    // 3. Membaca Memory (RAM)
    $mem_cmd = shell_exec('wmic OS get FreePhysicalMemory,TotalVisibleMemorySize /Value');
    $mem_lines = explode("\n", trim($mem_cmd));
    
    $free_mem = 0; $total_mem = 0;
    foreach ($mem_lines as $line) {
        if (strpos($line, 'FreePhysicalMemory') !== false) {
            $free_mem = (int) explode('=', $line)[1];
        }
        if (strpos($line, 'TotalVisibleMemorySize') !== false) {
            $total_mem = (int) explode('=', $line)[1];
        }
    }
    $mem_usage_percent = 0;
    if ($total_mem > 0) {
        $mem_usage_percent = round((($total_mem - $free_mem) / $total_mem) * 100, 2);
    }

    return [
        'cpu' => $cpu_load,
        'disk' => $disk_load,
        'memory' => $mem_usage_percent
    ];
}

echo json_encode(getSystemMetrics());
?>