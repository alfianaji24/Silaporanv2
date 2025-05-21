function hitungPotonganJam($jam_out, $jam_pulang)
{
    $jam_out = strtotime($jam_out);
    $jam_pulang = strtotime($jam_pulang);

    if ($jam_out < $jam_pulang) {
        $selisih = $jam_pulang - $jam_out;
        $jam = floor($selisih / 3600);
        $menit = floor(($selisih % 3600) / 60);

        // Konversi ke jam desimal (contoh: 1 jam 30 menit = 1.5 jam)
        return $jam + ($menit / 60);
    }

    return 0;
}
