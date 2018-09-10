<?php
namespace Akasima\RichShop\Models;

use Xpressengine\User\Models\User;
use Xpressengine\Plugins\Payment\Buyer as BuyerInterface;

class Buyer extends User implements BuyerInterface
{

    public function getName()
    {
        return $this->getDisplayName();
    }

    public function getEmail()
    {
        return $this->getAttribute('email');
    }

    public function getTelNum()
    {
        return '';
    }
}
