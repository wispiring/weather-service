<?php

namespace Wispiring\WeatherService\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RuntimeException;

class AssetsController
{
    public function serveAction(Application $app, Request $request, $file)
    {
        // http://stackoverflow.com/questions/2668854/sanitizing-strings-to-make-them-url-and-filename-safe
        // Remove anything which isn't a word, whitespace, number
        // or any of the following caracters -_~,;:[]().
        $file = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).\/])", '', $file);
        // Remove any runs of periods
        $file = preg_replace("([\.]{2,})", '', $file);

        $basePath = __DIR__.'/../../assets/';
        $filename = $basePath.$file;
        if (!file_exists($filename)) {
            throw new RuntimeException('File not found: '.$filename);
        }
        $options = [];
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'png':
                $options['Content-Type'] = 'image/png';
                break;
            case 'jpg':
                $options['Content-Type'] = 'image/jpg';
                break;
            case 'css':
                $options['Content-Type'] = 'text/css';
                break;
            case 'js':
                $options['Content-Type'] = 'application/javascript';
                break;
            default:
                $options['Content-Type'] = 'application/octet-stream';
                $options['Content-Disposition'] = 'attachment;filename="'.basename($filename).'"';
                break;
        }

        return new Response(file_get_contents($filename), 200, $options);
    }
}
