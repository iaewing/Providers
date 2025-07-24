<?php

namespace SocialiteProviders\Sonos;

use SocialiteProviders\Manager\SocialiteWasCalled;

class SonosExtendSocialite
{
    public function handle(SocialiteWasCalled $socialiteWasCalled): void
    {
        $socialiteWasCalled->extendSocialite('sonos', Provider::class);
    }
}
