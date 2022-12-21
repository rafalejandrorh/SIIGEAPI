<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Traza_User;
use App\Models\Traza_Funcionarios;
use App\Models\Traza_Token;
use App\Models\Traza_Servicios;
use App\Models\Traza_Roles;
use App\Models\Traza_Dependencias;
use App\Models\Traza_Sessions;
use App\Models\Traza_User_SIIPOL;

class TrazasListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $tabla = $event->getModule();
        $data = $event->getData();
        switch ($tabla) {
            case 'Traza_User':
                Traza_User::create($data);
                break;
            case 'Traza_Funcionarios':
                Traza_Funcionarios::create($data);
                break;
            case 'Traza_Token':
                Traza_Token::create($data);
                break;
            case 'Traza_Servicios':
                Traza_Servicios::Create($data);
                break;
            case 'Traza_Roles':
                Traza_Roles::Create($data);
                break;
            case 'Traza_Dependencias':
                Traza_Dependencias::Create($data);
                break;
            case 'Traza_Users_SIIPOL':
                Traza_User_SIIPOL::Create($data);
                break;
            case 'Traza_Sessions':
                Traza_Sessions::Create($data);
                break;
            default:
                break;
        }
    }
}
