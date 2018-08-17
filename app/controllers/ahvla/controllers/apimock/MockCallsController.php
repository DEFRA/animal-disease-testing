<?php

namespace ahvla\controllers\apimock;

use Input;
use Controller;

class MockCallsController extends Controller
{
    public function getClientsAction()
    {
        $mockedClients = [
            ['id' => 'AARDVARK', 'name' => 'Jonathan Giles', 'address' => 'Mill Valley Farm, Llanfilo Brecon, Powys LD3 5TU', 'location' => '302153,229543', 'cphh' => '03/1422/23'],
            ['id' => 'AARDVARK', 'name' => 'Eddie Redmayne', 'address' => 'Gilfillen Dairy Co-op, The Mount Hay on Wye, Powys HR3 6RS', 'location' => '326061,244333', 'cphh' => '34/4242/54/2'],
            ['id' => 'AARDVARK', 'name' => 'Gillian Wright', 'address' => 'Valley Milk and Dairy Farm, Maiden Croft Lane, Glastobury BA5 5KP', 'location' => '353305,147347', 'cphh' => '67/3442/21/8'],
            ['id' => '3', 'name' => 'Ken Williams', 'address' => 'Green Moor Farm, Crofton Hill, Crofton Cumbria CA5 6QD', 'location' => '330067,549810', 'cphh' => '5QX	23/7854/27'],
            ['id' => '3', 'name' => 'Seth Morton', 'address' => 'Sunny Vale, Butterleigh, Cullompton Devon EX13 2FG', 'location' => '329113,099728', 'cphh' => '45/1245/65'],
            ['id' => '3', 'name' => 'Richard Williams', 'address' => 'Butter Farm, Penybont Hall, Brecon LD2 6TT', 'location' => '304305,249528', 'cphh' => '36/7654/45'],
        ];

        $mockedClients = [
            ['pvsId' => 'ALPHAVET', 'address' => 'MAGPIE VALLEY FARM,BIRKBY, NORTHALLERTON,DL7 5RG', 'cphh' => '48-005-9996', 'clientId' => '48-005-9996', 'location' => 'NZ333027', 'name' => 'STAGG JM', 'location' => '431730,495431'],
            ['pvsId' => 'ALPHAVET', 'address' => 'FRIDAY FARM,KNOWSLEY LANE, WIGGINGTON, YORK, YO3 3DF', 'cphh' => '48-100-9997', 'clientId' => '48-100-9997', 'location' => 'SE562592', 'name' => 'SMITH, R', 'location' => '459466,458420'],
            ['pvsId' => 'ALPHAVET', 'address' => 'DILLITH, BARNWELL,RICHMOND,N YORKS,DL11 7DF', 'cphh' => '48-204-9998', 'clientId' => '48-204-9998', 'location' => 'NZ101101', 'name' => 'SCOTT, M', 'location' => '412828,513752']
        ];

        $input = Input::all();

        $textFilter = isset($input['filter']) ? $input['filter'] : '';
        $pvsId = isset($input['pvsId']) ? $input['pvsId'] : '-1';
        $matches = [];

        //Per each client
        foreach ($mockedClients as $mockedClient) {
            $ignoreClient = false;

            //Check if every other of filter shows in either name, address or cphh
            foreach (explode(' ', trim($textFilter)) as $filterWord) {
                if (trim($filterWord)) {
                    $wordFoundSomeWhere = false;
                    if (
                        $mockedClient['pvsId'] == $pvsId &&
                        (stripos($mockedClient['name'], $filterWord) !== false
                            || stripos($mockedClient['address'], $filterWord) !== false
                            || stripos($mockedClient['cphh'], $filterWord) !== false)
                    ) {
                        $wordFoundSomeWhere = true;
                    }

                    if (!$wordFoundSomeWhere) {
                        $ignoreClient = true; //At least one word of the text search was not found anywhere, jump out and check new client
                        break;
                    } else {
                        $matches[] = $mockedClient;
                    }
                }
            }
        }

        die(json_encode($matches));
    }

    public function getSubmissionsAction()
    {
        /*$mockedClients = [
            ['pvsid' => '14-C0003-01-15 1', 'status' => 'a status', 'type' => 'a type 1', 'submissionId' => '14-C0003-01-15'],
            ['pvsid' => '14-C0003-02-15 2', 'status' => 'a status', 'type' => 'a type 2', 'submissionId' => '14-C0003-02-15'],
            ['pvsid' => '14-C0003-03-15 3', 'status' => 'a status', 'type' => 'a type 3', 'submissionId' => '14-C0003-03-15']
        ];*/

        $mockedClients = [
            [   "changedStatusDate"=>"2015-03-03T00:00:00.0000000-00:00", "clinician"=>"Alpha Lastname",
                "draftSubmissionId"=>"101", "isCancelable"=>true, "isDigital"=>false, "resultsAvailable"=>false,
                "resultsDueDate"=>null, "status"=>"Draft", "submissionId"=>"VI-15-000001", "submittedById"=>"",
                "submittedDate"=>null, "type"=>null, "vioHasChanged"=>false
            ]
        ];

        $input = Input::all();

        $textFilter = isset($input['filter']) ? $input['filter'] : '';
        $pvsId = isset($input['pvsid']) ? $input['pvsid'] : '';
        $matches = [];

        //Per each client
        foreach ($mockedClients as $mockedClient) {
            $ignoreClient = false;

            //Check if every other of filter shows in either name, address or cphh
            foreach (explode(' ', trim($textFilter)) as $filterWord) {
                if (trim($filterWord)) {
                    $wordFoundSomeWhere = false;

                    if (isset($mockedClient['draftSubmissionId'])) {
                        if (
                            // $mockedClient['id'] == $pvsId &&
                        (stripos($mockedClient['draftSubmissionId'], $filterWord) !== false ||
                            stripos($mockedClient['draftSubmissionId'], $filterWord) !== false
                        )
                        ) {
                            $wordFoundSomeWhere = true;
                        }
                    }

                    if (!$wordFoundSomeWhere) {
                        $ignoreClient = true; //At least one word of the text search was not found anywhere, jump out and check new client
                        break;
                    }
                }
            }

            if (!$ignoreClient) {
                $matches[] = $mockedClient;
            }
        }

        return $matches;
    }

    public function getSamplesAction()
    {
        $result = [
            ['id' => '1', 'name' => 'Sample A']
        ];

        die(json_encode($result));
    }

    public function getProductsAction()
    {
        $mockedProducts = [
            ['id' => '1', 'name' => 'Culture, stained smears and wet preparation (for routine culture)', 'price' => 20, 'turnaround' => 0, 'species' => ['Cattle', 'Pig'], 'sampleTypes' => ['Blood', 'Fetal brain']],
            ['id' => '2', 'name' => 'PCR on fresh brain', 'price' => 35.5, 'turnaround' => 3, 'species' => ['Pig'], 'sampleTypes' => ['Blood']],
            ['id' => '3', 'name' => 'Bovine abortion/stillbirth serology package A  (L.hardjo/ N.caninum)', 'price' => 50, 'turnaround' => 7, 'species' => ['Bovine', 'Pig'], 'sampleTypes' => ['Faeces', 'Serum', 'Blood']],
            ['id' => '4', 'name' => 'Iodine assay', 'price' => 60, 'turnaround' => 5, 'species' => ['Avian'], 'sampleTypes' => ['Fetal brain']],
            ['id' => '5', 'name' => 'Enteric package for 6 – 21 day old calves', 'price' => 20.5, 'turnaround' => 4, 'species' => ['Sheep'], 'sampleTypes' => ['Blood', 'Fetal spleen or thymus']],
            ['id' => '6', 'name' => 'Paired ELISA antibody test', 'price' => 10, 'turnaround' => 6, 'species' => ['Cattle', 'Pig', 'Sheep'], 'sampleTypes' => ['Fetal spleen or thymus']]
        ];


        $filter = Input::get('filter', '');

        $matches = [];

        foreach ($mockedProducts as $mockedProduct) {
            $ignoreProduct = false;

            foreach (explode(' ', trim($filter)) as $filterWord) {
                $filterWord = trim($filterWord);
                if ($filterWord) {
                    $wordFoundSomeWhere = false;
                    if (stripos($mockedProduct['name'], $filterWord) !== false
                        || $this->findInNestedArray($mockedProduct['species'], $filterWord) !== false
                        || $this->findInNestedArray($mockedProduct['sampleTypes'], $filterWord) !== false
                    ) {
                        $wordFoundSomeWhere = true;
                    }

                    if (!$wordFoundSomeWhere) {
                        $ignoreProduct = true;
                        break;
                    }
                }
            }

            if (!$ignoreProduct) {
                $matches[] = $mockedProduct;
            }
        }

        die(json_encode($matches));
    }

    public function getDeliveryAddressesAction()
    {
        $productCount = Input::get('productCount', 1);

        $mockString = array();

        // simple rule, if one product, one address, more than 1 product, assume multiple addresses
        if ( $productCount == 1 ) {

            // only singleAddress set if one product

            $mockString['singleAddress'] = "APHA Field Services, Kendal Road, Harlescott, Shrewsbury, Shropshire, SY1 4HD";
        }
        else {

            // both are set if more than one product

            $mockString['singleAddress'] = "APHA Field Services, Kendal Road, Harlescott, Shrewsbury, Shropshire, SY1 4HD";

            $mockString['separateAddresses'] = array();

            $mockString['separateAddresses'][] = array(
                'address' => "APHA Field Services, Kendal Road,Harlescott, Shrewsbury, Shropshire, SY1 4HD",
                'tests' => array(array('animalId' => '42A', 'sampleId' => '101', 'sampleType' => 'blood', 'testId' => 'PC0203')
                ));

            $mockString['separateAddresses'][] = array(
                'address' => "APHA Veterinary Investigation Centre, Woodham Lane, New Haw, Addlestone, Surrey, KT15 3NB",
                'tests' => array(   array('animalId' => '50A', 'sampleId' => '102', 'sampleType' => 'blood', 'testId' => 'PC0203'),
                                    array('animalId' => '50B', 'sampleId' => '103', 'sampleType' => 'Serum', 'testId' => 'PC0208')
                ));
        }

        die(json_encode($mockString));
    }

    public function getLatestResults()
    {
        // report 2
        $productCount = '{
  "SampleComments": [

  ],
  "Sections": [
    {
      "Comments": [

      ],
      "Footnotes": [

      ],
      "Isolates": [

      ],
      "Name": "General Results",
      "SortOrder": 3,
      "SubSections": [
        {
          "Name": null,
          "Results": [
            {
              "Amended": false,
              "ComponentName": "GEN0040",
              "Result": "G491",
              "SampleName": "1 (Faeces - haemolytic <i>Escherichia coli<\/i>)",
              "SortOrder": 6000001,
              "TestName": "TC0040 <i>Eschericia coli<\/i> Serotype Result",
              "TestNumber": 13985867
            }
          ],
          "SortOrder": 0,
          "Type": "STANDARD"
        }
      ],
      "Type": "GENERIC"
    },
    {
      "Comments": [

      ],
      "Footnotes": [

      ],
      "Isolates": [
        {
          "Footnote": "<h3><b>Notes.<\/b><\/h3><p><font size=2>1) Ampicillin and amoxicillin have similar activity.<br>2) Enrofloxacin is chosen to represent the licensed fluoroquinolones (danofloxacin, marbofloxacin and enrofloxacin).<br>3) In general cross-resistance  exists between the tetracycline group (chlortetracycline, oxytetracycline and tetracycline).<br>4) Organisms sensitive to neomycin are generally also sensitive to framycetin.<br><\/font><\/p>",
          "Name": "<I>Klebsiella ozaenae<\/I>",
          "Reference": "IS14-00920",
          "SampleReference": "1 (Faeces - haemolytic <i>Escherichia coli<\/i>)",
          "Site": "Faeces",
          "Species": "CATTLE"
        },
        {
          "Footnote": "<h3><b>Notes.<\/b><\/h3><p><font size=2>1) Ampicillin and amoxicillin have similar activity.<br>2) Enrofloxacin is chosen to represent the licensed fluoroquinolones (danofloxacin, marbofloxacin and enrofloxacin).<br>3) In general cross-resistance  exists between the tetracycline group (chlortetracycline, oxytetracycline and tetracycline).<br>4) Organisms sensitive to neomycin are generally also sensitive to framycetin.<br><\/font><\/p>",
          "Name": "<I>Salmonella group B<\/I>",
          "Reference": "IS14-00921",
          "SampleReference": "2 (Faeces - <i>Salmonella<\/i> spp. group B pending ID)",
          "Site": "Faeces",
          "Species": "CATTLE"
        }
      ],
      "Name": "Sensitivity Testing",
      "SortOrder": 4,
      "SubSections": [
        {
          "Name": null,
          "Results": [
            {
              "Amended": false,
              "ComponentName": "RE_NAMC30",
              "Result": "S",
              "SampleName": "IS14-00920",
              "SortOrder": 2620003,
              "TestName": "Amoxicillin \/ Clavulanic acid  <font size=2>(30 \u00b5g)<\/font>",
              "TestNumber": 13985866
            },
            {
              "Amended": false,
              "ComponentName": "RE_NAMP10",
              "Result": "S",
              "SampleName": "IS14-00920",
              "SortOrder": 2620006,
              "TestName": "Ampicillin  <font size=2>(10 \u00b5g)<\/font>",
              "TestNumber": 13985866
            },
            {
              "Amended": false,
              "ComponentName": "RE_NAPR15",
              "Result": "S",
              "SampleName": "IS14-00920",
              "SortOrder": 2620018,
              "TestName": "Apramycin  <font size=2>(15 \u00b5g)<\/font>",
              "TestNumber": 13985866
            },
            {
              "Amended": false,
              "ComponentName": "RE_NENR5",
              "Result": "S",
              "SampleName": "IS14-00920",
              "SortOrder": 2620012,
              "TestName": "Enrofloxacin  <font size=2>(5 \u00b5g)<\/font>",
              "TestNumber": 13985866
            },
            {
              "Amended": false,
              "ComponentName": "RE_NN10",
              "Result": "S",
              "SampleName": "IS14-00920",
              "SortOrder": 2620009,
              "TestName": "Neomycin  <font size=2>(10 \u00b5g)<\/font>",
              "TestNumber": 13985866
            },
            {
              "Amended": false,
              "ComponentName": "RE_NSH25",
              "Result": "S",
              "SampleName": "IS14-00920",
              "SortOrder": 2620015,
              "TestName": "Spectinomycin  <font size=2>(25 \u00b5g)<\/font>",
              "TestNumber": 13985866
            },
            {
              "Amended": false,
              "ComponentName": "RE_NSXT25",
              "Result": "S",
              "SampleName": "IS14-00920",
              "SortOrder": 2620021,
              "TestName": "Trimethoprim \/ Sulphamethoxazole  <font size=2>(25 \u00b5g)<\/font>",
              "TestNumber": 13985866
            },
            {
              "Amended": false,
              "ComponentName": "RE_NTE10",
              "Result": "S",
              "SampleName": "IS14-00920",
              "SortOrder": 2620024,
              "TestName": "Tetracycline  <font size=2>(10 \u00b5g)<\/font>",
              "TestNumber": 13985866
            },
            {
              "Amended": false,
              "ComponentName": "RE_NAMC30",
              "Result": "S",
              "SampleName": "IS14-00921",
              "SortOrder": 2620003,
              "TestName": "Amoxicillin \/ Clavulanic acid  <font size=2>(30 \u00b5g)<\/font>",
              "TestNumber": 13985869
            },
            {
              "Amended": false,
              "ComponentName": "RE_NAMP10",
              "Result": "S",
              "SampleName": "IS14-00921",
              "SortOrder": 2620006,
              "TestName": "Ampicillin  <font size=2>(10 \u00b5g)<\/font>",
              "TestNumber": 13985869
            },
            {
              "Amended": false,
              "ComponentName": "RE_NAPR15",
              "Result": "S",
              "SampleName": "IS14-00921",
              "SortOrder": 2620018,
              "TestName": "Apramycin  <font size=2>(15 \u00b5g)<\/font>",
              "TestNumber": 13985869
            },
            {
              "Amended": false,
              "ComponentName": "RE_NENR5",
              "Result": "S",
              "SampleName": "IS14-00921",
              "SortOrder": 2620012,
              "TestName": "Enrofloxacin  <font size=2>(5 \u00b5g)<\/font>",
              "TestNumber": 13985869
            },
            {
              "Amended": false,
              "ComponentName": "RE_NN10",
              "Result": "S",
              "SampleName": "IS14-00921",
              "SortOrder": 2620009,
              "TestName": "Neomycin  <font size=2>(10 \u00b5g)<\/font>",
              "TestNumber": 13985869
            },
            {
              "Amended": false,
              "ComponentName": "RE_NSH25",
              "Result": "S",
              "SampleName": "IS14-00921",
              "SortOrder": 2620015,
              "TestName": "Spectinomycin  <font size=2>(25 \u00b5g)<\/font>",
              "TestNumber": 13985869
            },
            {
              "Amended": false,
              "ComponentName": "RE_NSXT25",
              "Result": "S",
              "SampleName": "IS14-00921",
              "SortOrder": 2620021,
              "TestName": "Trimethoprim \/ Sulphamethoxazole  <font size=2>(25 \u00b5g)<\/font>",
              "TestNumber": 13985869
            },
            {
              "Amended": false,
              "ComponentName": "RE_NTE10",
              "Result": "S",
              "SampleName": "IS14-00921",
              "SortOrder": 2620024,
              "TestName": "Tetracycline  <font size=2>(10 \u00b5g)<\/font>",
              "TestNumber": 13985869
            }
          ],
          "SortOrder": 0,
          "Type": "SENTEST"
        }
      ],
      "Type": "ISOLATE"
    },
    {
      "Comments": [

      ],
      "Footnotes": [
        {
          "ExtraSortOrder": 65,
          "Footnote": "<p>Post to Submitting Laboratory or Practice<br>CC: by email marked OFFICIAL - SENSITIVE to:<br>ZDRI mailbox (zdri@defra.gsi.gov.uk)<br>Lesley Larkin (Lesley.Larkin@ahvla.gsi.gov.uk)<br>Animal Health HQ (Salmonella.Worcester@ahvla.gsi.gov.uk)<br>Sheila Voas (veterinarydivision@scotland.gsi.gov.uk)<br>Andrew Frost (Andrew.Frost@ahvla.gsi.gov.uk)<\/p>",
          "TestNumbers": "13985870",
          "TestSortOrder": 7910001
        }
      ],
      "Isolates": [
        {
          "Footnote": null,
          "Name": "<I>Salmonella group B<\/I>",
          "Reference": "IS14-00921",
          "SampleReference": "2 (Faeces - <i>Salmonella<\/i> spp. group B pending ID)",
          "Site": "Faeces",
          "Species": "CATTLE"
        }
      ],
      "Name": "Salmonella Testing",
      "SortOrder": 6,
      "SubSections": [
        {
          "Name": "Salmonella: Serotyping",
          "Results": [
            {
              "Amended": false,
              "ComponentName": "Serotype",
              "Result": "JEDBURGH",
              "SampleName": "IS14-00921",
              "SortOrder": 7910001,
              "TestName": "TC0310 Serotype",
              "TestNumber": 13985870
            },
            {
              "Amended": false,
              "ComponentName": "Sub Genus",
              "Result": "I",
              "SampleName": "IS14-00921",
              "SortOrder": 7910002,
              "TestName": "TC0310 Sub Genus",
              "TestNumber": 13985870
            },
            {
              "Amended": false,
              "ComponentName": "Variant",
              "Result": "N\/A",
              "SampleName": "IS14-00921",
              "SortOrder": 7910003,
              "TestName": "TC0310 Variant",
              "TestNumber": 13985870
            }
          ],
          "SortOrder": 1,
          "Type": "STANDARD"
        }
      ],
      "Type": "SAMPLE"
    },
    {
      "Comments": [

      ],
      "Footnotes": [

      ],
      "Isolates": [

      ],
      "Name": "Primary Bacterial Culture",
      "SortOrder": 11,
      "SubSections": [
        {
          "Name": null,
          "Results": [
            {
              "Amended": false,
              "ComponentName": "GEN0101_1",
              "Result": "Heavy pure growth of haemolytic <i>Escherichia coli<\/i>.",
              "SampleName": "1 (Faeces)",
              "SortOrder": 6280003,
              "TestName": "TC0101 Bacteriology Result",
              "TestNumber": 13985861
            },
            {
              "Amended": false,
              "ComponentName": "GEN0101_1",
              "Result": "Moderate pure growth of <i>Salmonella<\/i> spp. group B pending ID.",
              "SampleName": "2 (Faeces)",
              "SortOrder": 6280003,
              "TestName": "TC0101 Bacteriology Result",
              "TestNumber": 13985862
            },
            {
              "Amended": false,
              "ComponentName": "GEN0101_1",
              "Result": "Mixed flora containing a moderate growth of haemolytic <i>Escherichia coli<\/i> and a moderate growth of <i>Salmonella<\/i> spp. group B pending ID.",
              "SampleName": "3 (Faeces)",
              "SortOrder": 6280003,
              "TestName": "TC0101 Bacteriology Result",
              "TestNumber": 13985863
            },
            {
              "Amended": false,
              "ComponentName": "GEN0101_1",
              "Result": "Moderate pure growth of non-haemolytic <i>Escherichia coli<\/i>.",
              "SampleName": "4 (Faeces)",
              "SortOrder": 6280003,
              "TestName": "TC0101 Bacteriology Result",
              "TestNumber": 13985864
            }
          ],
          "SortOrder": 0,
          "Type": "STANDARD"
        }
      ],
      "Type": "GENERIC"
    },
    {
      "Comments": [

      ],
      "Footnotes": [

      ],
      "Isolates": [

      ],
      "Name": "Microbiology",
      "SortOrder": 16,
      "SubSections": [
        {
          "Name": null,
          "Results": [
            {
              "Amended": false,
              "ComponentName": "GEN0829_K99",
              "Result": "POSITIVE",
              "SampleName": "3 (Faeces - haemolytic <i>Escherichia coli<\/i>)",
              "SortOrder": 12690001,
              "TestName": "TC0829 <i>E. coli<\/i> K99 (F5) fimbrial adhesion (&dagger;)",
              "TestNumber": 13985871
            }
          ],
          "SortOrder": 0,
          "Type": "STANDARD"
        }
      ],
      "Type": "GENERIC"
    },
    {
      "Comments": [

      ],
      "Footnotes": [

      ],
      "Isolates": [

      ],
      "Name": "Isolate Identification",
      "SortOrder": 18,
      "SubSections": [
        {
          "Name": null,
          "Results": [
            {
              "Amended": false,
              "ComponentName": "GEN0102",
              "Result": "<i>Klebsiella ozaenae<\/i>",
              "SampleName": "1 (Faeces - haemolytic <i>Escherichia coli<\/i>)",
              "SortOrder": 6290001,
              "TestName": "TC0102 Bacterial ID",
              "TestNumber": 13985865
            }
          ],
          "SortOrder": 0,
          "Type": "STANDARD"
        }
      ],
      "Type": "GENERIC"
    }
  ]
}';

        // report 1
        $productCount = '{
  "SampleComments": [

  ],
  "Sections": [
    {
      "Comments": [

      ],
      "Footnotes": [

      ],
      "Isolates": [

      ],
      "Name": "General Results",
      "SortOrder": 3,
      "SubSections": [
        {
          "Name": null,
          "Results": [
            {
              "Amended": false,
              "ComponentName": "GEN0040",
              "Result": "G491",
              "SampleName": "456 (Faeces - non-haemolytic <i>Escherichia coli<\/i>)",
              "SortOrder": 6000001,
              "TestName": "TC0040 <i>Eschericia coli<\/i> Serotype Result",
              "TestNumber": 13985883
            }
          ],
          "SortOrder": 0,
          "Type": "STANDARD"
        }
      ],
      "Type": "GENERIC"
    },
    {
      "Comments": [

      ],
      "Footnotes": [

      ],
      "Isolates": [
        {
          "Footnote": "<h3><b>Notes.<\/b><\/h3><p><font size=2>1) Ampicillin and amoxicillin have similar activity.<br>2) Enrofloxacin is chosen to represent the licensed fluoroquinolones (danofloxacin, marbofloxacin and enrofloxacin).<br>3) In general cross-resistance  exists between the tetracycline group (chlortetracycline, oxytetracycline and tetracycline).<br>4) Organisms sensitive to neomycin are generally also sensitive to framycetin.<br><\/font><\/p>",
          "Name": "<I>Klebsiella ozaenae<\/I>",
          "Reference": "IS14-00925",
          "SampleReference": "456 (Faeces - non-haemolytic <i>Escherichia coli<\/i>)",
          "Site": "Faeces",
          "Species": "CATTLE"
        },
        {
          "Footnote": "<h3><b>Notes.<\/b><\/h3><p><font size=2>1) Ampicillin and amoxicillin have similar activity.<br>2) Enrofloxacin is chosen to represent the licensed fluoroquinolones (danofloxacin, marbofloxacin and enrofloxacin).<br>3) In general cross-resistance  exists between the tetracycline group (chlortetracycline, oxytetracycline and tetracycline).<br>4) Organisms sensitive to neomycin are generally also sensitive to framycetin.<br><\/font><\/p>",
          "Name": "<I>Salmonella group B<\/I>",
          "Reference": "IS14-00926",
          "SampleReference": "496 (Faeces - <i>Salmonella<\/i> spp. group B pending ID)",
          "Site": "Faeces",
          "Species": "CATTLE"
        }
      ],
      "Name": "Sensitivity Testing",
      "SortOrder": 4,
      "SubSections": [
        {
          "Name": null,
          "Results": [
            {
              "Amended": false,
              "ComponentName": "RE_NAMC30",
              "Result": "S",
              "SampleName": "IS14-00925",
              "SortOrder": 2620003,
              "TestName": "Amoxicillin \/ Clavulanic acid  <font size=2>(30 \u00b5g)<\/font>",
              "TestNumber": 13985884
            },
            {
              "Amended": false,
              "ComponentName": "RE_NAMP10",
              "Result": "S",
              "SampleName": "IS14-00925",
              "SortOrder": 2620006,
              "TestName": "Ampicillin  <font size=2>(10 \u00b5g)<\/font>",
              "TestNumber": 13985884
            },
            {
              "Amended": false,
              "ComponentName": "RE_NAPR15",
              "Result": "S",
              "SampleName": "IS14-00925",
              "SortOrder": 2620018,
              "TestName": "Apramycin  <font size=2>(15 \u00b5g)<\/font>",
              "TestNumber": 13985884
            },
            {
              "Amended": false,
              "ComponentName": "RE_NENR5",
              "Result": "S",
              "SampleName": "IS14-00925",
              "SortOrder": 2620012,
              "TestName": "Enrofloxacin  <font size=2>(5 \u00b5g)<\/font>",
              "TestNumber": 13985884
            },
            {
              "Amended": false,
              "ComponentName": "RE_NN10",
              "Result": "S",
              "SampleName": "IS14-00925",
              "SortOrder": 2620009,
              "TestName": "Neomycin  <font size=2>(10 \u00b5g)<\/font>",
              "TestNumber": 13985884
            },
            {
              "Amended": false,
              "ComponentName": "RE_NSH25",
              "Result": "S",
              "SampleName": "IS14-00925",
              "SortOrder": 2620015,
              "TestName": "Spectinomycin  <font size=2>(25 \u00b5g)<\/font>",
              "TestNumber": 13985884
            },
            {
              "Amended": false,
              "ComponentName": "RE_NSXT25",
              "Result": "S",
              "SampleName": "IS14-00925",
              "SortOrder": 2620021,
              "TestName": "Trimethoprim \/ Sulphamethoxazole  <font size=2>(25 \u00b5g)<\/font>",
              "TestNumber": 13985884
            },
            {
              "Amended": false,
              "ComponentName": "RE_NTE10",
              "Result": "S",
              "SampleName": "IS14-00925",
              "SortOrder": 2620024,
              "TestName": "Tetracycline  <font size=2>(10 \u00b5g)<\/font>",
              "TestNumber": 13985884
            },
            {
              "Amended": false,
              "ComponentName": "RE_NAMC30",
              "Result": "S",
              "SampleName": "IS14-00926",
              "SortOrder": 2620003,
              "TestName": "Amoxicillin \/ Clavulanic acid  <font size=2>(30 \u00b5g)<\/font>",
              "TestNumber": 13985887
            },
            {
              "Amended": false,
              "ComponentName": "RE_NAMP10",
              "Result": "R",
              "SampleName": "IS14-00926",
              "SortOrder": 2620006,
              "TestName": "Ampicillin  <font size=2>(10 \u00b5g)<\/font>",
              "TestNumber": 13985887
            },
            {
              "Amended": false,
              "ComponentName": "RE_NAPR15",
              "Result": "S",
              "SampleName": "IS14-00926",
              "SortOrder": 2620018,
              "TestName": "Apramycin  <font size=2>(15 \u00b5g)<\/font>",
              "TestNumber": 13985887
            },
            {
              "Amended": false,
              "ComponentName": "RE_NENR5",
              "Result": "S",
              "SampleName": "IS14-00926",
              "SortOrder": 2620012,
              "TestName": "Enrofloxacin  <font size=2>(5 \u00b5g)<\/font>",
              "TestNumber": 13985887
            },
            {
              "Amended": false,
              "ComponentName": "RE_NN10",
              "Result": "S",
              "SampleName": "IS14-00926",
              "SortOrder": 2620009,
              "TestName": "Neomycin  <font size=2>(10 \u00b5g)<\/font>",
              "TestNumber": 13985887
            },
            {
              "Amended": false,
              "ComponentName": "RE_NSH25",
              "Result": "S",
              "SampleName": "IS14-00926",
              "SortOrder": 2620015,
              "TestName": "Spectinomycin  <font size=2>(25 \u00b5g)<\/font>",
              "TestNumber": 13985887
            },
            {
              "Amended": false,
              "ComponentName": "RE_NSXT25",
              "Result": "S",
              "SampleName": "IS14-00926",
              "SortOrder": 2620021,
              "TestName": "Trimethoprim \/ Sulphamethoxazole  <font size=2>(25 \u00b5g)<\/font>",
              "TestNumber": 13985887
            },
            {
              "Amended": false,
              "ComponentName": "RE_NTE10",
              "Result": "S",
              "SampleName": "IS14-00926",
              "SortOrder": 2620024,
              "TestName": "Tetracycline  <font size=2>(10 \u00b5g)<\/font>",
              "TestNumber": 13985887
            }
          ],
          "SortOrder": 0,
          "Type": "SENTEST"
        }
      ],
      "Type": "ISOLATE"
    },
    {
      "Comments": [

      ],
      "Footnotes": [
        {
          "ExtraSortOrder": 65,
          "Footnote": "<p>Post to Submitting Laboratory or Practice<br>CC: by email marked OFFICIAL - SENSITIVE to:<br>ZDRI mailbox (zdri@defra.gsi.gov.uk)<br>Lesley Larkin (Lesley.Larkin@ahvla.gsi.gov.uk)<br>Animal Health HQ (Salmonella.Worcester@ahvla.gsi.gov.uk)<br>Sheila Voas (veterinarydivision@scotland.gsi.gov.uk)<br>Andrew Frost (Andrew.Frost@ahvla.gsi.gov.uk)<\/p>",
          "TestNumbers": "13985886",
          "TestSortOrder": 7910001
        }
      ],
      "Isolates": [
        {
          "Footnote": null,
          "Name": "<I>Salmonella group B<\/I>",
          "Reference": "IS14-00926",
          "SampleReference": "496 (Faeces - <i>Salmonella<\/i> spp. group B pending ID)",
          "Site": "Faeces",
          "Species": "CATTLE"
        }
      ],
      "Name": "Salmonella Testing",
      "SortOrder": 6,
      "SubSections": [
        {
          "Name": "Salmonella: Serotyping",
          "Results": [
            {
              "Amended": false,
              "ComponentName": "Serotype",
              "Result": "JEDBURGH",
              "SampleName": "IS14-00926",
              "SortOrder": 7910001,
              "TestName": "TC0310 Serotype",
              "TestNumber": 13985886
            },
            {
              "Amended": false,
              "ComponentName": "Sub Genus",
              "Result": "I",
              "SampleName": "IS14-00926",
              "SortOrder": 7910002,
              "TestName": "TC0310 Sub Genus",
              "TestNumber": 13985886
            },
            {
              "Amended": false,
              "ComponentName": "Variant",
              "Result": "N\/A",
              "SampleName": "IS14-00926",
              "SortOrder": 7910003,
              "TestName": "TC0310 Variant",
              "TestNumber": 13985886
            }
          ],
          "SortOrder": 1,
          "Type": "STANDARD"
        }
      ],
      "Type": "SAMPLE"
    },
    {
      "Comments": [

      ],
      "Footnotes": [

      ],
      "Isolates": [

      ],
      "Name": "Microbiology",
      "SortOrder": 16,
      "SubSections": [
        {
          "Name": null,
          "Results": [
            {
              "Amended": false,
              "ComponentName": "GEN0829_K99",
              "Result": "POSITIVE",
              "SampleName": "223 (Faeces - non-haemolytic <i>Escherichia coli<\/i>)",
              "SortOrder": 12690001,
              "TestName": "TC0829 <i>E. coli<\/i> K99 (F5) fimbrial adhesion (&dagger;)",
              "TestNumber": 13985888
            }
          ],
          "SortOrder": 0,
          "Type": "STANDARD"
        }
      ],
      "Type": "GENERIC"
    },
    {
      "Comments": [

      ],
      "Footnotes": [

      ],
      "Isolates": [

      ],
      "Name": "Isolate Identification",
      "SortOrder": 18,
      "SubSections": [
        {
          "Name": null,
          "Results": [
            {
              "Amended": false,
              "ComponentName": "GEN0102",
              "Result": "<i>Klebsiella ozaenae<\/i>",
              "SampleName": "456 (Faeces - non-haemolytic <i>Escherichia coli<\/i>)",
              "SortOrder": 6290001,
              "TestName": "TC0102 Bacterial ID",
              "TestNumber": 13985882
            }
          ],
          "SortOrder": 0,
          "Type": "STANDARD"
        }
      ],
      "Type": "GENERIC"
    }
  ]
}';

        $productCount = str_replace(chr(10),'',$productCount);
        $productCount = str_replace(chr(13),'',$productCount);

        die($productCount);
    }
}