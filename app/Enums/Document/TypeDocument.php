<?php

namespace App\Enums\Document;

enum TypeDocument: string
{
    case CONTRACT = 'contract';
    case RECEIPT = 'receipt';
    case CERTIFICATE = 'certificate';
    case IDCOPY = 'id_copy';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::CONTRACT => 'Contrato',
            self::RECEIPT => 'Recibo',
            self::CERTIFICATE => 'Certificado',
            self::IDCOPY => 'Copia de Identificación',
            self::OTHER => 'Otro',
        };
    }
}
