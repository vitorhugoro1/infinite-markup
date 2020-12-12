<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Partner') }}
            </h2>

            <a href="{{ route('partner.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Create Partner') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($partners as $partner)
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                <div class="font-semibold">
                                    {{ $partner->name }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="1" class="px-6 py-4 whitespace-no-wrap">
                                {{ __('Not has Partner.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $partners->links('layouts.pagination') }}
            </div>
        </div>
    </div>
</x-app-layout>