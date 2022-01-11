@extends('frontend.layouts.app')
@section('title', 'Scan and Pay')
@section('content')

    <div class="scan_and_pay">
        <div class="card my-card">
            <div class="card-body text-center">
                <div class="text-center">
                    <img src="{{ asset('img/scan.png') }}" alt="">
                </div>
                <p class="mb-2">Scan QR code</p>
                <button class="btn btn-theme" data-toggle="modal" data-target="#scanModal">Scan</button>

                <!-- Modal -->
                <div class="modal fade" id="scanModal" tabindex="-1" role="dialog" aria-labelledby="scanModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Scan & Pay</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <video id="scanner" width=100% height="300px"></video>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('frontend/js/qr-scanner.umd.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var videoElem = document.getElementById('scanner');
            
            const qrScanner = new QrScanner(videoElem, function(result) {
                if(result){
                    $('#scanModal').modal('hide')
                    qrScanner.stop();
                }
                console.log(result);
            });

            $('#scanModal').on('shown.bs.modal', function(e) {
                qrScanner.start();
            });

            $('#scanModal').on('hidden.bs.modal', function(e) {
                qrScanner.stop();
            });
        });
    </script>

@endsection
