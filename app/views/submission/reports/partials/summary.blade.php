<a id="view-pdf" href="/reports/pdf?draftSubmissionId={{ $submission->submissionId }}">View PDF</a>
<table>
    <tr>
        <td><strong>APHA Reference Number</strong></td>
        <td><strong>@if(isset($submission)) {{ $submission->submissionId }} @endif</strong></td>
    </tr>
    <tr>
        <td>Your Reference</td>
        <td>@if(isset($submission)) {{ $submission->sendersReference }} @endif</td>
    </tr>
    <tr>
        <td>Previous Reference</td>
        <td>@if(isset($submission)) {{ $submission->previousSubmissionId }} @endif</td>
    </tr>
    <tr>
        <td>Owner</td>
        <td>@if(isset($submission)) {{ $submission->clientName }} @endif</td>
    </tr>
    <tr>
        <td>Client Farm</td>
        <td>@if(isset($submission)) {{ $submission->clientFarm }} @endif</td>
    </tr>
    <tr>
        <td>CPH</td>
        <td>@if(isset($submission)) {{ $submission->clientCPHH }} @endif</td>
    </tr>
    <tr>
        <td>Date Received</td>
        <td>@if(isset($submission)) {{ date('d/m/Y',strtotime($submission->limsSubmittedDate)) }} @endif</td>
    </tr>
    <tr>
        <td>Date of Sampling</td>
        <td>@if(isset($submission)) {{ $submission->dateSamplesTaken ? $submission->dateSamplesTaken->format('d/m/Y') : '' }} @endif</td>
    </tr>
    <tr>
        <td>Case Vet</td>
        <td>@if (empty($submission->clinician)) --- @else{{ $submission->clinician }}@endif</td>
    </tr>
    <tr>
        <td>Species / Breed</td>
        <td>@if(isset($submission)) {{ $submission->animalSpecies->getDescription() }} / {{ $submission->animalBreed->getDescription() }} @endif</td>
    </tr>
    <tr>
        <td>Sex / Age</td>
        <td>@if(isset($submission)) {{ $submission->animalSex->getDescription() }} / {{ $submission->animalAge->getDescription() }} @endif</td>
    </tr>
    <tr>
        <td>Samples</td>
        <td>
            <?php

                if (is_array($results['samples'])) {
                    foreach($results['samples'] as $sample) {
                        if(!empty($sample['sampleType'])) {
                            echo $sample['sampleType'] . ' x ' . $sample['quantity'];
                            echo '<br />';
                        }
                    }
                }

            ?>
        </td>
    </tr>
    <tr>
        <td>Animal IDs</td>
        <td>
            <?php

                if (is_array($submission->animalIds)) {
                    for($idx=0;$idx<sizeof($submission->animalIds);$idx++) {
                        echo $submission->animalIds[$idx]->description.' ';
                    }
                }

            ?>
        </td>
    </tr>
    <tr>
        <td>Sub. Reason</td>
        <td>@if(isset($submission)) {{ $submission->type }} @endif</td>
    </tr>
    <tr>
        <td>Status</td>
        <td>@if(isset($submission)) {{ $submission->status }} @endif</td>
    </tr>
</table>