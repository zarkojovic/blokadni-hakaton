<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GeminiService {

    protected $client;

    protected $apiKey;

    public function __construct() {
        $this->client = new Client();
        $this->apiKey = config('services.gemini.api_key');
    }

    public function generateResponse($elaboratFile) {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$this->apiKey";

        $prompt = 'You are an AI assistant specialized in text processing and summarization. Your task is to generate two structured documents from a given elaboration document ("Elaborat"). The first document ("Idiot") is a summary of the elaboration with a limit of 300 words. The second document ("Tabular Overview") is a structured table that organizes the key points from the elaboration document into a predefined format. Instructions: Summarization (Idiot) Generate a concise summary of the elaboration, limited to 300 words. Preserve the key ideas, goals, and strategies while making the text clear and impactful. The summary should logically cover the purpose, timeline, and planned actions of the initiative described in the elaboration. The summary must start with a title that is relevant to the content of the elaboration. The title and the summary must be separated by ### on a new line. The response should contain only the title and the summary, without any additional text. Tabular Overview Extract key information from the elaboration document and format it into a structured text-based table. The table must include the following exact sections, written in Serbian Cyrillic: ОПИС Циљ Циљна група Бројност Ризик (репресија, спиновање) Импакт Напомена ПРОЦЕДУРА Тип Сатница Место Тон акције Обавезне акције Актери Трасе Безбедност Ширење информација The output must strictly follow this structure. No additional sections should be included. The response should be in plain text without any extra formatting, decorations, or symbols. Important Requirements: The response should only contain the generated content for "Idiot" and "Tabular Overview". No introductory or explanatory text should be included. The two sections must be separated by a unique delimiter: %%% (three percentage signs). No decorative elements (such as asterisks, dashes, or additional formatting) should be present. The response must be in Serbian Cyrillic. Input: The full text of the elaboration document is provided below. Use this content as the source material for generating the required outputs. Output Format: ИДИОТ (Сажетак у 300 речи) - Serbian Cyrillic Title (contextual and relevant) ### (Separator between title and summary) Summary text %%% (Separator between the two documents) Табеларни приказ (Structured Table) - Serbian Cyrillic';

        // Read the file content and encode it in base64
        $fileContent = file_get_contents($elaboratFile->getPathname());
        $base64FileContent = base64_encode($fileContent);

        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt,
                        ],
                        [
                            'inline_data' => [
                                'data' => $base64FileContent,
                                'mime_type' => $elaboratFile->getMimeType(),
                            ],
                        ],
                    ],
                ],
            ],
        ];

        try {
            $response = $this->client->post($url, [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json'],
            ]);

            $body = json_decode($response->getBody(), TRUE);
            return $body['candidates'][0]['content']['parts'][0]['text'] ?? 'No response';
        }
        catch (RequestException $e) {
            return $e->getMessage();
        }
    }

}


// Протест 15. марта има за циљ да изврши притисак на власт кроз масовно окупљање уз учешће синдиката, радника, просветара и других.
// Мисија протеста је "Враћање суверенитета народу", а циљеви укључују генерални штрајк од најмање 3 дана, смене у тужилаштву, промене у РТС-у и РЕМ-у, окупљање 250.000 људи и интензивну медијску промоцију протеста.
// Блокираће се четири зоне у трајању од 3 сата: Булевар Краља Александра, Аутокоманда, Општина Нови Београд и Трг Републике.
// За сваку зону задужени су факултети који се налазе у близини. Блокаде ће трајати од 13:00 до 16:00 (11:30-14:30 за зону 3), након чега се учесници крећу ка Скупштини где је планирано окупљање од 16:00 до 17:00, а протест од 17:00 до 21:00/22:00.
// Пре протеста, организују се позиви радницима кроз медијску кампању и мини акције "Call centre" за контактирање медија.
// Израђује се промотивни материјал и планирају мање акције укључујући протесте "Раме уз раме" и "Операција" ТМФ-а.
// На дан протеста, пленуми одлучују о активностима у зонама. Испред Скупштине планиран је "Народни дневник", музички програм, говори студената и хор испред Цркве Светог Марка.
// Након 15. марта, планира се даља радикализација акција и генерални штрајк.

// Враћање суверенитета народу
// Target Audience: Студенти, радници, синдикати, просветари, грађани
// Estimated Number of Participants: 250.000
// Risks: Неслагање са циљевима, раздор међу студентима, потенцијалне опасности за одређене институције
// Expected Impact: Испуњење захтева студената, системске промене, притисак на власт, подстрек за даљу борбу
// Schedule of Events: - Пре протеста: Медијска кампања, позиви радницима, акције, промотивни материјал - 15. март:
// Блокаде (13:00-16:00), окупљање испред Скупштине (16:00-17:00), протест (17:00-21:00/22:00), "Народни дневник",
// музички програм, говори - После 15. марта: Радикализација акција, генерални штрајк Locations: Булевар Краља Александра,
// Аутокоманда, Општина Нови Београд, Трг Републике, испред Скупштине
// Security Measures: Минимално 2000 редара, обезбеђивање кључних локација (Скупштина, РТС, Градска скупштина, Влада, Председништво), барикаде
// Logistics: Логистички центри у близини зона (факултети), шатори, ограде, пролази за хитну помоћ, смештај за студенте из других градова, сарадња са таксистима
// Media Plan: Мини акција „Call centre“, контактирање домаћих и страних медија, јавних личности, инфлуенсера, медијска промоција протеста
