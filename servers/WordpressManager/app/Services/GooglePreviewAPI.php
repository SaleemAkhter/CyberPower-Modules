<?php

namespace ModulesGarden\WordpressManager\App\Services;

use Exception;

class GooglePreviewAPI
{
    private $googleApiToken;
    private $error;
    private const DEFAULT_LANG = 'en';
    private const STRATEGIES   = ['desktop', 'mobile'];
    private const SOURCE       = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';


    public function __construct($token)
    {
        $this->googleApiToken = $token;
    }

    public function getGooglePageSpeedData($url, $locale = self::DEFAULT_LANG)
    {
        return $this->process([
                'url'    => $url,
                'locale' => $locale,
            ]
        );
    }

    private function process($params)
    {
        $strategiesResult = $this->processStrategies($params);
        $this->validateStrategiesResult($strategiesResult);
        $strategiesResult = $this->addPageScreenshot($strategiesResult);

        return $strategiesResult;
    }


    private function processStrategies($params)
    {
        $strategiesResult = [];

        foreach (self::STRATEGIES as $strategy)
        {
            try
            {
                $strategiesResult[$strategy] = $this->processStrategy($strategy, $params);
            }
            catch (\Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }

        return $strategiesResult;
    }

    private function validateStrategiesResult($strategiesResult)
    {
        if (empty($strategiesResult))
        {
            throw new \Exception('Cannot fetch information about website');
        }
    }

    private function addPageScreenshot($strategiesResult)
    {
        foreach ($strategiesResult as $strategy)
        {
            if(!empty($strategy['lighthouseResult']['audits']['final-screenshot']['details']['data']))
            {
                $strategiesResult['screenshot'] = $strategy['lighthouseResult']['audits']['final-screenshot']['details']['data'];
                break;
            }
        }

        return $strategiesResult;
    }


    /**
     * @param $strategy
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    private function processStrategy($strategy, $params)
    {
        $response = $this->request(array_merge($params, [
            'strategy'  => $strategy,
            'key'       => $this->googleApiToken
        ]));

        return $this->parseResponse($response);
    }

    /**
     * @param $response
     * @return mixed
     * @throws \Exception
     */
    private function parseResponse($response)
    {
        $parsedResponse = json_decode($response, true);
        if (!$parsedResponse)
        {
            throw new \Exception('Invalid response. Cannot decode JSON');
        }

        if (!empty($parsedResponse['error']))
        {
            throw new \Exception($parsedResponse['error']['message'], $parsedResponse['error']['code']);
        }

        return $parsedResponse;
    }

    /**
     * @param $params
     * @return bool|string
     * @throws \Exception
     */
    private function request($params)
    {
        $ch = curl_init();

        $source = self::SOURCE . '?' . http_build_query($params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $source);
        $response = curl_exec($ch);

        if ($response == null)
        {
            $error = curl_error($ch);
            curl_close($ch);

            throw new \Exception($error);
        }

        return $response;
    }
}
