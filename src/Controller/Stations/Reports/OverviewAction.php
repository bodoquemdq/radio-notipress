<?php

declare(strict_types=1);

namespace App\Controller\Stations\Reports;

use App\Entity;
use App\Http\Response;
use App\Http\ServerRequest;
use Psr\Http\Message\ResponseInterface;

final class OverviewAction
{
    public function __construct(
        private readonly Entity\Repository\SettingsRepository $settingsRepo
    ) {
    }

    public function __invoke(
        ServerRequest $request,
        Response $response,
        string $station_id
    ): ResponseInterface {
        // Get current analytics level.
        if (!$this->settingsRepo->readSettings()->isAnalyticsEnabled()) {
            // The entirety of the dashboard can't be shown, so redirect user to the profile page.
            return $request->getView()->renderToResponse($response, 'stations/reports/restricted');
        }

        $router = $request->getRouter();

        return $request->getView()->renderVuePage(
            response: $response,
            component: 'Vue_StationsReportsOverview',
            id: 'vue-reports-overview',
            title: __('Statistics Overview'),
            props: [
                'chartsUrl' => (string)$router->fromHere('api:stations:reports:overview-charts'),
                'bestAndWorstUrl' => (string)$router->fromHere('api:stations:reports:best-and-worst'),
                'mostPlayedUrl' => (string)$router->fromHere('api:stations:reports:most-played'),
            ]
        );
    }
}
