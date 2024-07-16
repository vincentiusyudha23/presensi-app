<?php

namespace App\Observers;

use App\Models\Petugas;

class PetugasObserver
{
    /**
     * Handle the Petugas "created" event.
     *
     * @param  \App\Models\Petugas  $petugas
     * @return void
     */
    public function created(Petugas $petugas)
    {
        //
    }

    /**
     * Handle the Petugas "updated" event.
     *
     * @param  \App\Models\Petugas  $petugas
     * @return void
     */
    public function updated(Petugas $petugas)
    {
        //
    }

    /**
     * Handle the Petugas "deleted" event.
     *
     * @param  \App\Models\Petugas  $petugas
     * @return void
     */
    public function deleted(Petugas $petugas)
    {
        \App\Models\User::find($petugas->user_id)->update([
            'username' => $petugas->user->username . '-deleted',
        ]);
        $petugas->update([
            'email' => $petugas->email . '-deleted',
            'nik' => $petugas->nik . '-deleted',
        ]);
    }

    /**
     * Handle the Petugas "restored" event.
     *
     * @param  \App\Models\Petugas  $petugas
     * @return void
     */
    public function restored(Petugas $petugas)
    {
        //
    }

    /**
     * Handle the Petugas "force deleted" event.
     *
     * @param  \App\Models\Petugas  $petugas
     * @return void
     */
    public function forceDeleted(Petugas $petugas)
    {
        //
    }
}
