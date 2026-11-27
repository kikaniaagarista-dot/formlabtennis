<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TennisClass extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'description',
        'price',
        'capacity',
        'is_active',
        'type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'type' => 'string',
    ];

    /**
     * Daftar paket: jumlah sesi dan diskon (dalam persen).
     * Ubah angka di sini kalau diskon berubah.
     */
    public const PACKAGES = [
        'per_meeting' => ['sessions' => 1, 'discount' => 0,    'label' => 'Pay Per Meeting'],
        'monthly_4'   => ['sessions' => 4, 'discount' => 6.25, 'label' => 'Monthly Membership 4 Sessions'],
        'monthly_6'   => ['sessions' => 6, 'discount' => 10,   'label' => 'Monthly Membership 6 Sessions'],
    ];

    /**
     * Harga paket per peserta, dihitung dari harga kelas yang diatur admin.
     * Contoh harga kelas 100.000:
     *   per_meeting = 100.000
     *   monthly_4   = 100.000 x 4 x (1 - 6,25%) = 375.000
     *   monthly_6   = 100.000 x 6 x (1 - 10%)   = 540.000
     */
    public function packagePrice(string $package): int
    {
        $config = self::PACKAGES[$package] ?? null;

        if (! $config) {
            throw new \InvalidArgumentException("Paket tidak dikenal: {$package}");
        }

        $normalPrice = (float) $this->price * $config['sessions'];

        return (int) round($normalPrice * (100 - $config['discount']) / 100);
    }

    /**
     * Semua harga paket sekaligus, dipakai di halaman booking.
     */
    public function packagePrices(): array
    {
        $prices = [];

        foreach (array_keys(self::PACKAGES) as $package) {
            $prices[$package] = $this->packagePrice($package);
        }

        return $prices;
    }
}