public function laporan()
{
    return $this->hasMany(Laporan::class, 'pelapor_id');
}

public function peninjauan()
{
    return $this->hasMany(Peninjauan::class, 'pemantau_id');
}

public function penerusanInformasi()
{
    return $this->hasMany(PenerusanInformasi::class, 'koordinator_id');
}