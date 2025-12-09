<?php

namespace App\Enums;

enum IdentificationType: string
{
    case ID_CARD = 'id_card';
    case RUC = 'ruc';
    case PASSPORT = 'passport';
    case FINAL_CONSUMER = 'final_consumer';

    public function label(): string
    {
        return match($this) {
            self::ID_CARD => 'Cédula',
            self::RUC => 'RUC',
            self::PASSPORT => 'Pasaporte',
            self::FINAL_CONSUMER => 'Consumidor Final',
        };
    }
}
