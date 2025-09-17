<?php
namespace OnePieceTCGCollect\src\Services;

class EbayApi
{
    private static string $endpoint = "https://api.ebay.com/buy/browse/v1/item_summary/search";
    private static string $token = 'EBAY_API_TOKEN'

    public static function getLastSoldPrice(string $version , string $number , string $extension): ?array
    {
        $queries = [
            $number . " " . $version . " " . $extension . " japanese",
            $number . " " . $version . " " . $extension
        ];

        foreach ($queries as $index => $query) {
            $params = [
                'q' => $query,
                'limit' => 1,
                'sort' => 'price'
            ];

            $url = self::$endpoint . '?' . http_build_query($params);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer " . self::$token,
                "Content-Type: application/json",
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            if (!$response) continue;

            $data = json_decode($response, true);

            if (!empty($data['itemSummaries'][0]['price']['value'])) {
                $price = (float) $data['itemSummaries'][0]['price']['value'];
                $urlAnnonce = $data['itemSummaries'][0]['itemWebUrl'] ?? '';

                $note = ($index === 1) ? "(autre langue)" : "";

                return [
                    'price' => $price,
                    'url' => $urlAnnonce,
                    'note' => $note
                ];
            }
        }

        return null;
    }
}
