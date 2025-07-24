<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Pengajuan Kredit Baru') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            @if ($errors->any())
                                <ul class="mt-3 list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <form method="POST" action="{{ route('teller.applications.store') }}" x-data="{
                        selectedCategory: '{{ old('application_category', '') }}',
                        allParameters: {{ Js::from($parameters) }}
                    }">
                        @csrf

                        <h3 class="h5 mb-4">Data Nasabah</h3>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <x-input-label for="customer_name" :value="__('Nama Lengkap')" />
                                <x-text-input id="customer_name" class="form-control @error('customer_name') is-invalid @enderror" type="text" name="customer_name" :value="old('customer_name')" required autofocus />
                                <x-input-error :messages="$errors->get('customer_name')" class="mt-2" />
                            </div>
                            <div class="col-md-6">
                                <x-input-label for="customer_nik" :value="__('NIK (Nomor Induk Kependudukan)')" />
                                <x-text-input id="customer_nik" class="form-control @error('customer_nik') is-invalid @enderror" type="text" name="customer_nik" :value="old('customer_nik')" required maxlength="16" />
                                <x-input-error :messages="$errors->get('customer_nik')" class="mt-2" />
                            </div>
                            <div class="col-md-6">
                                <x-input-label for="customer_phone_number" :value="__('Nomor Telepon')" />
                                <x-text-input id="customer_phone_number" class="form-control @error('customer_phone_number') is-invalid @enderror" type="text" name="customer_phone_number" :value="old('customer_phone_number')" />
                                <x-input-error :messages="$errors->get('customer_phone_number')" class="mt-2" />
                            </div>
                            <div class="col-12">
                                <x-input-label for="customer_address" :value="__('Alamat Lengkap')" />
                                <textarea id="customer_address" name="customer_address" rows="3" class="form-control @error('customer_address') is-invalid @enderror">{{ old('customer_address') }}</textarea>
                                <x-input-error :messages="$errors->get('customer_address')" class="mt-2" />
                            </div>
                        </div>

                        <h3 class="h5 mb-4">Detail Pengajuan Kredit</h3>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <x-input-label for="loan_amount" :value="__('Jumlah Pinjaman (Rp)')" />
                                <x-text-input id="loan_amount" class="form-control @error('loan_amount') is-invalid @enderror" type="number" name="loan_amount" :value="old('loan_amount')" required min="1000000" step="100000" />
                                <x-input-error :messages="$errors->get('loan_amount')" class="mt-2" />
                            </div>
                            <div class="col-md-6">
                                <x-input-label for="tenor_months" :value="__('Jangka Waktu (Bulan)')" />
                                <x-text-input id="tenor_months" class="form-control @error('tenor_months') is-invalid @enderror" type="number" name="tenor_months" :value="old('tenor_months')" required min="1" max="60" />
                                <x-input-error :messages="$errors->get('tenor_months')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="application_category" :value="__('Kategori Nasabah')" />
                            <select id="application_category" name="application_category" x-model="selectedCategory" class="form-select @error('application_category') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('application_category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('application_category')" class="mt-2" />
                        </div>

                        <h3 class="h5 mb-4">Parameter Penentu Skor</h3>
                        <div x-show="selectedCategory" class="p-4 border rounded bg-light mb-4">
                            <template x-if="selectedCategory === 'UMKM/Pengusaha'">
                                <div class="row g-3">
                                    <template x-for="parameter in allParameters['UMKM/Pengusaha']" :key="parameter.id">
                                        <div class="col-md-6">
                                            <x-input-label :for="`param_${parameter.id}`" :value="parameter.parameter_name" />
                                            <input type="hidden" :name="`scoring_inputs[${parameter.id}][parameter_id]`" :value="parameter.id">

                                            <template x-if="parameter.rules.type === 'discrete'">
                                                <select :id="`param_${parameter.id}`" :name="`scoring_inputs[${parameter.id}][value]`" class="form-select" required>
                                                    <option value="">Pilih...</option>
                                                    <template x-for="option in parameter.rules.options" :key="option.value">
                                                        <option :value="option.value" x-text="option.value"></option>
                                                    </template>
                                                </select>
                                            </template>
                                            <template x-if="parameter.rules.type === 'range'">
                                                <x-text-input :id="`param_${parameter.id}`" class="form-control" type="number" step="any" :name="`scoring_inputs[${parameter.id}][value]`" required />
                                            </template>
                                            <x-input-error :messages="$errors->get(`scoring_inputs.${parameter.id}.value`)" class="mt-2" />
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="selectedCategory === 'Pegawai'">
                                <div class="row g-3">
                                    <template x-for="parameter in allParameters['Pegawai']" :key="parameter.id">
                                        <div class="col-md-6">
                                            <x-input-label :for="`param_${parameter.id}`" :value="parameter.parameter_name" />
                                            <input type="hidden" :name="`scoring_inputs[${parameter.id}][parameter_id]`" :value="parameter.id">

                                            <template x-if="parameter.rules.type === 'discrete'">
                                                <select :id="`param_${parameter.id}`" :name="`scoring_inputs[${parameter.id}][value]`" class="form-select" required>
                                                    <option value="">Pilih...</option>
                                                    <template x-for="option in parameter.rules.options" :key="option.value">
                                                        <option :value="option.value" x-text="option.value"></option>
                                                    </template>
                                                </select>
                                            </template>
                                            <template x-if="parameter.rules.type === 'range'">
                                                <x-text-input :id="`param_${parameter.id}`" class="form-control" type="number" step="any" :name="`scoring_inputs[${parameter.id}][value]`" required />
                                            </template>
                                            <x-input-error :messages="$errors->get(`scoring_inputs.${parameter.id}.value`)" class="mt-2" />
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                        <div x-show="!selectedCategory" class="text-muted fst-italic mt-4">
                            Pilih kategori nasabah di atas untuk menampilkan parameter scoring yang relevan.
                        </div>


                        <div class="d-flex justify-content-end mt-4">
                            <x-primary-button>
                                {{ __('Ajukan Kredit') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
