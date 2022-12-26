<?php

namespace ModulesGarden\WordpressManager\App\Models;

class SpeedTest extends WebsiteDetails
{

    protected $id;

    protected $loadingExperience;

    protected $firstContentfulPaint;
    protected $firstInputDelay;
    protected $largestContentfulPaint;
    protected $cumulativeLayoutShiftScore;
    protected $overallCategory;

    protected $audits;
    protected $opportunities;
    protected $pageStats;
    protected $diagnostics;
    protected $screenshot;


    public function __construct(array $speedTest)
    {
        $this->setId($speedTest['id']);
        $this->setLoadingExp($speedTest);
        $this->setAudits($speedTest['lighthouseResult']['audits']);
    }

    public function setId($id)
    {
        return $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLoadingExp(): array
    {
        return $this->loadingExperience;
    }

    public function getScreenshot()
    {
        return $this->screenshot;
    }

    public function getDiagnostics()
    {
        return $this->diagnostics;
    }

    private function getPageStats()
    {
        return $this->pageStats;
    }

    public function getAudits()
    {
        return $this->audits;
    }

    private function setAudits($data)
    {
        $this->setDiagnostics($data['diagnostics']);
        $this->setPageStats($data);
        $this->setScreenshot($data['final-screenshot']['details']['data']);

        $audits        = [];
        $opportunities = [];
        foreach ($data as $audit)
        {
            if ($audit['details']['type'] == 'opportunity' && $audit['score'] < 1)
            {
                $opportunities[] = [
                    'title'       => $audit['title'],
                    'description' => $audit['description'],
                ];
            }
            if ($audit['score'] >= 1)
            {
                $audits[] = [
                    'title'       => $audit['title'],
                    'description' => $audit['description'],
                ];
            }
        }

        $this->opportunities = $opportunities;

        return $this->audits = $audits;
    }

    private function getOpportunities()
    {
        return $this->opportunities;
    }

    private function setPageStats($audits)
    {
        $this->pageStats = [
            'fcp'         => $audits['first-contentful-paint'],
            'lcpe'        => $audits['largest-contentful-paint-element'],
            'tbt'         => $audits['total-blocking-time'],
            'ds'          => $audits['dom-size'],
            'si'          => $audits['speed-index'],
            'interactive' => $audits['interactive'],
            'cls'         => $audits['cumulative-layout-shift'],
        ];
    }

    private function setDiagnostics($diagnostics)
    {
        $this->diagnostics = $diagnostics;
    }

    private function setLoadingExp(array $speedTest)
    {
        $this->firstContentfulPaint       = $this->convert($speedTest['loadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS'] ?? []);
        $this->cumulativeLayoutShiftScore = $this->convert($speedTest['originLoadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE'] ?? []);
        $this->firstInputDelay            = $this->convert($speedTest['loadingExperience']['metrics']['FIRST_INPUT_DELAY_MS'] ?? $speedTest['originLoadingExperience']['metrics']['FIRST_INPUT_DELAY_MS'] ?? []);
        $this->largestContentfulPaint     = $this->convert($speedTest['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS'] ?? $speedTest['originLoadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS'] ?? []);


        $this->overallCategory = $speedTest['loadingExperience']['overall_category'];

        $this->loadingExperience = [
            'overall_category' => $this->overallCategory,

            'metrics' => [
                'FIRST_CONTENTFUL_PAINT_MS'     => $this->firstContentfulPaint,
                'FIRST_INPUT_DELAY_MS'          => $this->firstInputDelay,
                'LARGEST_CONTENTFUL_PAINT_MS'   => $this->largestContentfulPaint,
                'CUMULATIVE_LAYOUT_SHIFT_SCORE' => $this->cumulativeLayoutShiftScore,
            ]
        ];
    }

    private function setScreenshot($screenshotData)
    {
        return $this->screenshot = $screenshotData;
    }

    private function convert(array $data)
    {
        $convertedData = [];
        foreach ($data['distributions'] as $convertedValue)
        {
            $convertedData[] = $convertedValue['proportion'];
        }

        $result = [
            'percentile' => $data['percentile'],
            'median'     => $convertedData,
            'category'   => $data['category'],
        ];

        return $result;
    }

    public function toArray()
    {
        return [
            'id'                => $this->getId(),
            'screenshot'        => $this->getScreenshot(),
            'loadingExperience' => $this->getLoadingExp(),
            'pageStats'         => $this->getPageStats(),
            'audits'            => $this->getAudits(),
            'diagnostics'       => $this->getDiagnostics(),
            'opportunities'     => $this->getOpportunities(),
        ];
    }
}
