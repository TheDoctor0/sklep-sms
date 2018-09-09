<?php
namespace App\Kernels;

use App\Middlewares\RequireNotInstalledOrNotUpdated;
use App\ShopState;
use App\Template;
use Install\Full;
use Install\OldShop;
use Install\Update;
use Install\UpdateInfo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InstallKernel extends Kernel
{
    protected $middlewares = [
        RequireNotInstalledOrNotUpdated::class,
    ];

    public function run(Request $request)
    {
        /** @var OldShop $oldShop */
        $oldShop = $this->app->make(OldShop::class);
        $oldShop->checkForConfigFile();

        if (!ShopState::isInstalled()) {
            return $this->full();
        }

        /** @var ShopState $shopState */
        $shopState = $this->app->make(ShopState::class);
        if (!$shopState->isUpToDate()) {
            return $this->update();
        }

        return new Response("Sklep nie wymaga aktualizacji. Przejdź na stronę sklepu usuwająć z paska adresu /install");
    }

    protected function full()
    {
        /** @var Template $template */
        $template = $this->app->make(Template::class);

        /** @var Full $full */
        $full = $this->app->make(Full::class);

        list($modules, $files_priv) = $full->get();

        // #########################################
        // ##########    Wyświetl dane    ##########
        // #########################################

        $files_privilages = '';
        foreach ($files_priv as $file) {
            if ($file == "") {
                continue;
            }

            if (is_writable($this->app->path($file))) {
                $privilage = "ok";
            } else {
                $privilage = "bad";
            }

            $files_privilages .= eval($template->install_full_render('file_privilages'));
        }

        $server_modules = '';
        foreach ($modules as $module) {
            if ($module['value']) {
                $status = "correct";
                $title = "Prawidłowo";
            } else {
                $status = "incorrect";
                $title = "Nieprawidłowo";
            }

            $server_modules .= eval($template->install_full_render('module'));
        }

        $notifyHttpServer = $this->generateHttpServerNotification();

        // Pobranie ostatecznego szablonu
        $output = eval($template->install_full_render('index'));

        return new Response($output);
    }

    protected function update()
    {
        /** @var Template $template */
        $template = $this->app->make(Template::class);

        /** @var UpdateInfo $updateInfo */
        $updateInfo = $this->app->make(UpdateInfo::class);

        /** @var Update $update */
        $update = $this->app->make(Update::class);

        list($modules, $files_priv, $files_del) = $update->get();

        $everything_ok = true;
        // Pobieramy informacje o plikach ktore sa git i te ktore sa be
        $update_info = $updateInfo->updateInfo($everything_ok, $files_priv, $files_del, $modules);
        $class = $everything_ok ? "ok" : "bad";

        $notifyHttpServer = $this->generateHttpServerNotification();

        // Pobranie ostatecznego szablonu
        $output = eval($template->install_update_render('index'));

        return new Response($output);
    }

    protected function generateHttpServerNotification()
    {
        /** @var Template $template */
        $template = $this->app->make(Template::class);

        if (str_contains(strtolower($_SERVER["SERVER_SOFTWARE"]), 'apache')) {
            return '';
        }

        return eval($template->install_render('http_server_notification'));
    }
}
