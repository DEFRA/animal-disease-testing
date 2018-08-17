@extends('layouts.master')
@section('title', 'Submission ' . $submission_data['Submission']['Reference number'] . ' -')
@section('head')
@stop
@section('content')

    <h2>View submission</h2>

    <div class="submission-overview">

        <?php $i=1 ?>
        @foreach ($submission_data as $group_title => $items)

            <h3>{{{$i . '. ' . $group_title}}}</h3>

            <table>

                @foreach ($items as $property => $value)

                    <tr>
                        <td class="one-half">{{{$property}}}</td>
                        <td class="one-half">{{{strip_tags($value)}}}</td>
                    </tr>

                @endforeach
            </table>

            <?php $i++ ?>
        @endforeach

        <h3>5. Sample addresses</h3>

        <?php
            $address_config = null;
            if ($send_samples_package === 'separate') {
                $address_config = 'separate';
            } elseif ($send_samples_package === 'together')  {
                $address_config = 'single';
            }
        ?>

        <div class="submission-overview-samples">

            <?php
            $record = 0;
            ?>

            @foreach ($sample_addresses as $addressId => $address )
                <div class="submission-overview-samples-item">
                    <p class="font-xsmall">
                        {{{$address->address1}}}<br>
                        {{{$address->address2}}}<br>
                        {{{$address->address3}}}<br>
                        <a href="mailto:{{{$address->labEmail}}}">{{{$address->labEmail}}}</a>
                    </p>
                    <table class="samples">
                        <thead>
                        <tr>
                            <th class="one-quarter">Sample type</th>
                            <th class="one-quarter">Test IDs</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (isset($address->sampleTypes))
                            @foreach ($address->sampleTypes as $sampleType)
                                <tr>
                                    <td>{{{$sampleType['sampleType']}}}</td>
                                    <td>{{{$sampleType['tests']}}}</td>
                                </tr>
                            @endforeach
                        @endif

                        </tbody>
                    </table>
                    <form action="/print-address-label?_token={{{csrf_token()}}}&draftSubmissionId={{{$submission_data['Submission']['Reference number']}}}&amp;address_config={{{$address_config}}}&amp;lab_id={{{$address->labId}}}" class="form float--left" method="POST" target="_blank">
                        <input type="submit" id="printaddresslabel{{{$record}}}" name="printaddresslabel" value="Print Address Label" class="button">
                    </form>
                    <form action="/print-dispatch-note?_token={{{csrf_token()}}}&draftSubmissionId={{{$submission_data['Submission']['Reference number']}}}&amp;address_config={{{$address_config}}}&amp;lab_id={{{$address->labId}}}" class="form" method="POST" target="_blank">
                        <input type="submit" id="printdispatchnote{{{$record}}}" name="printdispatchnote" value="Print Dispatch Note" class="button">
                    </form>
                </div>

                <?php
                $record++;
                ?>
            @endforeach
        </div>

        <h3>6. Tests</h3>
            @if (!empty($charges))
                <table>
                    <thead>
                    <tr>
                        <th>Test ID</th>
                        <th>Test name</th>
                        <th>Qty</th>
                        <th>Unit price</th>
                        <th>Fee</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($charges as $charge)
                            @if (empty($charge->constituentTests))
                                <tr> {{-- Test --}}
                                    <td>{{{$charge->code}}}</td>
                                    <td>{{{$charge->description}}}</td>
                                    <td class="text--center">{{{$charge->quantity}}}</td>
                                    <td class="text--right">{{{'&pound;'.$charge->unitPrice}}}</td>
                                    <td class="text--right">{{{'&pound;'.$charge->totalPrice}}}</td>
                                </tr>
                            @else {{-- Package --}}
                                <tr class="detail">
                                    <td>{{{$charge->code}}}</td>
                                    <td>{{{$charge->description}}}</td>
                                    <td class="text--center">{{{$charge->quantity}}}</td>
                                    <td class="text--right">{{{'&pound;'.$charge->unitPrice}}}</td>
                                    <td class="text--right">{{{'&pound;'.$charge->totalPrice}}}</td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                        <details>
                                            <summary><span class="summary">Test details</span></summary>
                                            <div class="panel panel-border-narrow">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>Test ID</th>
                                                            <th>Test name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $testCounter = 0; ?>
                                                        @foreach ($charge->constituentTests as $constituentTest)
                                                            <tr>
                                                                <td>{{{$constituentTest['code']}}}</td>
                                                                <td>{{{$constituentTest['description']}}}</td>
                                                            </tr>
                                                            <?php $testCounter++; ?>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </details>
                                    <td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No charges available currently.</p>
            @endif

        <table>
            <tr>
                <td>Total submission cost</td>
                <td>{{{'&pound;'.$total_submission_cost}}}</td>
            </tr>
        </table>

        <h3>7. Tests status</h3>

        <table>
            <thead>
            <tr>
                <th>Test ID</th>
                <th>Test name</th>
                <th>Qty</th>
                <th>Status</th>
                <th>Results due</th>
            </tr>
            </thead>
            <tbody>
            @if (!empty($test_status))
                @foreach ($test_status as $status)
                    <tr class="detail">
                        <td>{{{$status->code}}}</td>
                        <td>{{{$status->description}}}</td>
                        <td class="text--center">{{{$status->quantity}}}</td>
                        <td>{{{$status->status}}}</td>
                        <td>{{{!empty($status->resultsDueDate) ? date('d/m/Y',strtotime($status->resultsDueDate)) : ''}}}</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <details>
                                <summary><span class="summary">Sample details</span></summary>
                                <div class="panel panel-border-narrow">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Sample type</th>
                                                <th>Sample ID</th>
                                                <th>Animal ID</th>
                                                <th>Pool group</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($status->samples as $sample)
                                                <tr>
                                                    <td>{{{$sample->sampleType}}}</td>
                                                    <td>{{{$sample->sampleId}}}</td>
                                                    <td>{{{$sample->animalId}}}</td>
                                                    <td>{{{$sample->poolGroup}}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </details>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

@stop