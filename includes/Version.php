<?php
namespace App;

use App\Requesting\Requester;

class Version
{
    /** @var Requester */
    protected $requester;

    public function __construct(Requester $requester)
    {
        $this->requester = $requester;
    }

    public function getNewestWeb()
    {
        if (getenv('LICENSE') === "false") {
            $response = $this->requester->get('https://api.github.com/repos/TheDoctor0/sklep-sms/releases/latest');
        } else {
            $response = $this->requester->get('https://api.github.com/repos/gammerce/sklep-sms/releases/latest');
        }

        $content = $response ? $response->json() : null;

        return array_get($content, 'tag_name');
    }

    public function getNewestAmxmodx()
    {
        if (getenv('LICENSE') === "false") {
            $response = $this->requester->get('https://api.github.com/repos/TheDoctor0/sklep-sms/releases/latest');
        } else {
            $response = $this->requester->get('https://api.github.com/repos/gammerce/plugin-amxmodx/releases/latest');
        }

        $content = $response ? $response->json() : null;

        return array_get($content, 'tag_name');
    }

    public function getNewestSourcemod()
    {
        // SM plugin not yet available.
        if (getenv('LICENSE') === "false") {
            return null;
        }

        $response = $this->requester->get('https://api.github.com/repos/gammerce/plugin-sourcemod/releases/latest');
        $content = $response ? $response->json() : null;

        return array_get($content, 'tag_name');
    }
}
