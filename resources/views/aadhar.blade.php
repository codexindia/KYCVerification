@extends('layouts.main')
@section('mainContent')
    <div
        class="mx-auto w-11/12 sm:w-full max-w-lg p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
        <form class="space-y-6" method="post" action="{{ route('aadhar_otp') }}">
            @csrf

            @if ($errors->any())
                <div class="flex p-4 mb-4 text-base text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                    role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Danger</span>
                    <div>
                        <span class="font-medium">Ensure that these requirements are met:</span>
                        <ul class="mt-1.5 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            @endif



            <div class="bg-white py-3 rounded text-center">
                <h1 class="text-2xl font-bold">Aadhar Verification</h1>


                <div>
                    <label for="aadhar_number" class="block mb-2 text-base font-medium text-gray-900 dark:text-white">Aadhar Card Number</label>
                    <input type="aadhar_number" name="aadhar_number" id="aadhar_number"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        placeholder="123512351235" value="{{ old('aadhar_number') }}" required />
                </div>


                <button type="submit"
                    class="mt-3 w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-base px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Continue</button>
            </div>



        </form>
    </div>

@endsection
@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {

            function OTPInput() {
                const inputs = document.querySelectorAll('#otp > *[id]');

                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].addEventListener('keydown', function(event) {

                        if (event.key === "Backspace") {
                            if (i !== 0 && inputs[i].value.length == 0)
                                inputs[i - 1].focus();
                        } else {
                            if (i === inputs.length - 1 && inputs[i].value !== '') {
                                return true;
                            } else if (inputs[i].value.length >= 4) {
                                if (i !== inputs.length - 1)
                                    inputs[i + 1].focus();
                                inputs[i + 1].value = event.key;
                                event.preventDefault();
                            }
                        }
                    });
                }
            }
            OTPInput();
        });
    </script>
@endsection
