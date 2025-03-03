<?php

declare(strict_types=1);

namespace Trx\Domain\Enum;

enum ContractType: string
{
    case TRC10 = 'trc10';
    case TRC20 = 'trc20';
    case TRC721 = 'trc721';
}
