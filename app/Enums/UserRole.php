<?php

namespace App\Enums;

enum UserRole: string
{
    case deputyHead = 'DeputyHead';
    case invoiceAuthorizer = 'InvoiceAuthoriser';
    case deputyMember = 'DeputyMember';
    case chairman = 'Chairman';
    case boardMember = 'BoardMember';
    case accountant = 'Accountant';
    case clientContact = 'ClientContact';

    public function order(): int
    {
        return match ($this) {
            self::clientContact => 1,
            self::accountant => 1,
            self::invoiceAuthorizer => 1,
            self::chairman => 2,
            self::deputyHead => 3,
            self::boardMember => 4,
            self::deputyMember => 5,
        };
    }

    public function visibleInMenu(): bool
    {
        return match ($this) {
            self::boardMember, self::chairman, self::deputyMember, self::deputyHead => true,
            default => false,
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::clientContact => 'F',
            self::accountant => 'R',
            self::boardMember => 'M',
            self::chairman => 'L',
            self::deputyHead => 'N',
            self::deputyMember => 'V',
            self::invoiceAuthorizer => '$',
        };
    }

    public function label()
    {
        return match ($this) {
            self::clientContact => 'Forretningsfører',
            self::accountant => 'Regnskapsfører',
            self::boardMember => 'Styremedlem',
            self::chairman => 'Styreleder',
            self::deputyHead => 'Nestleder',
            self::deputyMember => 'Varamedlem',
            self::invoiceAuthorizer => 'Fakturaattestant',
        };
    }
}
