<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Graph\Pages;


use Google\ApiCore\PagedListResponse;
use Google\Cloud\Monitoring\V3\Aggregation;
use Google\Cloud\Monitoring\V3\Aggregation\Aligner;
use Google\Cloud\Monitoring\V3\ListTimeSeriesRequest\TimeSeriesView;
use Google\Cloud\Monitoring\V3\MetricServiceClient;
use Google\Cloud\Monitoring\V3\TimeInterval;
use Google\Protobuf\Duration;
use Google\Protobuf\Timestamp;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\MetricServiceClientFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\ProjectFactory;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Graphs\Line;

class AbstractGoogleGraph extends Line
{
    public function listTimeSeries(string $filter): PagedListResponse
    {
        $project = (new ProjectFactory())->fromParams();
        $config = (new MetricServiceClientFactory())->fromParams();
        $client = new MetricServiceClient($config);
        $formattedProjectName = $client::projectName($project);

        $startTime = new Timestamp();
        $startTime->setSeconds(time() - (86400)); //24 hours
        $endTime = new Timestamp();
        $endTime->setSeconds(time());

        $interval = new TimeInterval();
        $interval->setStartTime($startTime);
        $interval->setEndTime($endTime);

        $view = TimeSeriesView::FULL;

        $alignmentPeriod = new Duration();
        $alignmentPeriod->setSeconds(3600); //1 hour
        $aggregation = new Aggregation();
        $aggregation->setAlignmentPeriod($alignmentPeriod);
        $aggregation->setPerSeriesAligner(Aligner::ALIGN_MEAN);

        $result =  $client->listTimeSeries(
            $formattedProjectName,
            $filter,
            $interval,
            $view,
            ['aggregation' => $aggregation]);

        $client->close();

        return $result;
    }

    public function getTimeLabels(): array
    {
        $date = new \DateTime();
        $labels = [];

        for ($i = 0; $i <= 23; $i++) {
            $labels[] = date('H:i', $date->getTimestamp());
            $date->modify('- 1hours');
        }

        return $labels;
    }
}