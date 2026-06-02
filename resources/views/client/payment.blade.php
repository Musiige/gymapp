<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Complete Payment</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Package summary --}}
            <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-5">
                <h3 class="font-semibold text-indigo-900 mb-3">Package Summary</h3>
                <p class="text-indigo-800">Package:
                    <span class="font-bold">{{ $subscription->membership->name }}</span>
                </p>
                <p class="text-indigo-800">Total Amount:
                    <span class="font-bold">UGX {{ number_format($subscription->membership->price) }}</span>
                </p>
                @if($payment)
                    <p class="text-indigo-800">Already Paid:
                        <span class="font-bold">UGX {{ number_format($payment->amount_paid) }}</span>
                    </p>
                    <p class="text-indigo-800">Remaining Balance:
                        <span class="font-bold text-red-600">UGX {{ number_format($payment->balance) }}</span>
                    </p>
                    <p class="mt-2">Status:
                        <span class="font-bold uppercase text-sm px-2 py-1 rounded-full
                            {{ $payment->status === 'paid' ? 'bg-green-100 text-green-700' :
                               ($payment->status === 'half-paid' ? 'bg-yellow-100 text-yellow-700' :
                               'bg-red-100 text-red-700') }}">
                            {{ $payment->status }}
                        </span>
                    </p>
                @endif
            </div>

            @if(!$payment || $payment->status !== 'paid')
                {{-- Payment form --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Make Payment</h3>

                    <form method="POST" action="{{ route('client.payment.process', $subscription->id) }}"
                        class="space-y-4">
                        @csrf

                        {{-- Payment method --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-3 border border-gray-200 rounded-lg p-3 cursor-pointer hover:border-indigo-400 transition">
                                    <input type="radio" name="payment_method" value="momo" class="text-indigo-600" required>
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">MTN MoMo</p>
                                        <p class="text-xs text-gray-500">Mobile Money</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 border border-gray-200 rounded-lg p-3 cursor-pointer hover:border-indigo-400 transition">
                                    <input type="radio" name="payment_method" value="airtel" class="text-indigo-600">
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">Airtel Pay</p>
                                        <p class="text-xs text-gray-500">Airtel Money</p>
                                    </div>
                                </label>
                            </div>
                            @error('payment_method')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone number --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Mobile Money Phone Number
                            </label>
                            <input type="text" name="phone"
                                value="{{ old('phone', Auth::user()->phone) }}"
                                placeholder="e.g. 0771234567"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Amount --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Amount to Pay (UGX)
                            </label>
                            <input type="number" name="amount"
                                value="{{ old('amount', $payment ? $payment->balance : $subscription->membership->price) }}"
                                min="1000"
                                max="{{ $subscription->membership->price }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="text-xs text-gray-400 mt-1">
                                You can pay partially. Full amount is
                                UGX {{ number_format($subscription->membership->price) }}.
                            </p>
                            @error('amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            style="background-color:#4f46e5; color:#ffffff;"
                            class="w-full font-semibold py-3 px-4 rounded-md transition hover:opacity-90">
                            Pay Now
                        </button>

                    </form>
                </div>
            @else
                <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                    <p class="text-green-700 font-semibold text-lg">Payment Complete</p>
                    <p class="text-green-600 text-sm mt-1">Your membership is fully paid and active.</p>
                    <a href="{{ route('client.dashboard') }}"
                        style="background-color:#4f46e5; color:#ffffff;"
                        class="inline-block mt-4 font-semibold py-2 px-6 rounded-md text-sm">
                        Go to Dashboard
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>