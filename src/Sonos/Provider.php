<?php

namespace SocialiteProviders\Sonos;

use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider
{
    public const IDENTIFIER = 'SONOS';

    /**
     * {@inheritdoc}
     */
    protected $scopes = ['playback-control-all'];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://api.sonos.com/login/v3/oauth', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://api.sonos.com/login/v3/oauth/access';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://api.ws.sonos.com/api/v1/households', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        // Sonos API returns households, not a user profile.
        // We'll use the first household's ID as the user ID.
        $householdId = $user['households'][0]['id'] ?? null;

        return (new User)->setRaw($user)->map([
            'household_id'       => $householdId,
            'nickname'           => null,
            'name'               => null,
            'email'              => null,
            'avatar'             => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}
