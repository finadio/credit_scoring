<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Tambah Parameter Scoring Baru') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.parameters.store') }}" x-data="{ rulesType: '{{ old('rules_type', 'discrete') }}', options: @json(old('options', [['value' => '', 'min' => '', 'max' => '', 'score' => '']])) }">
                        @csrf

                        <!-- Nama Parameter -->
                        <div class="mb-3">
                            <x-input-label for="parameter_name" :value="__('Nama Parameter')" />
                            <x-text-input id="parameter_name" class="form-control @error('parameter_name') is-invalid @enderror" type="text" name="parameter_name" :value="old('parameter_name')" required autofocus />
                            <x-input-error :messages="$errors->get('parameter_name')" class="mt-2" />
                        </div>

                        <!-- Kategori -->
                        <div class="mb-3">
                            <x-input-label for="category" :value="__('Kategori')" />
                            <select id="category" name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Tipe Aturan Scoring -->
                        <div class="mb-3">
                            <x-input-label :value="__('Tipe Aturan Scoring')" />
                            <div class="mt-2 d-flex gap-3">
                                <div class="form-check">
                                    <input type="radio" name="rules_type" value="discrete" x-model="rulesType" id="rules_type_discrete" class="form-check-input">
                                    <label class="form-check-label" for="rules_type_discrete">Diskrit (Pilihan, misal: "Baik", "Buruk")</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="rules_type" value="range" x-model="rulesType" id="rules_type_range" class="form-check-input">
                                    <label class="form-check-label" for="rules_type_range">Rentang (Numerik, misal: 0-100)</label>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('rules_type')" class="mt-2" />
                        </div>

                        <!-- Aturan Opsi/Rentang -->
                        <div class="mb-4 p-4 border rounded bg-light">
                            <h4 class="h6 mb-3">Aturan Opsi/Rentang</h4>

                            <template x-for="(option, index) in options" :key="index">
                                <div class="row g-2 mb-3 align-items-end p-2 border rounded bg-white">
                                    <div x-show="rulesType === 'discrete'" class="col-md-6">
                                        <x-input-label :for="`options_${index}_value`" :value="__('Nilai Opsi')" />
                                        <x-text-input :id="`options_${index}_value`" class="form-control" type="text" :name="`options[${index}][value]`" x-model="option.value" :required="rulesType === 'discrete'" />
                                        <x-input-error :messages="$errors->get(`options.${index}.value`)" class="mt-2" />
                                    </div>

                                    <div x-show="rulesType === 'range'" class="col-md-3">
                                        <x-input-label :for="`options_${index}_min`" :value="__('Min (Opsional)')" />
                                        <x-text-input :id="`options_${index}_min`" class="form-control" type="number" step="any" :name="`options[${index}][min]`" x-model="option.min" />
                                        <x-input-error :messages="$errors->get(`options.${index}.min`)" class="mt-2" />
                                    </div>
                                    <div x-show="rulesType === 'range'" class="col-md-3">
                                        <x-input-label :for="`options_${index}_max`" :value="__('Max (Opsional)')" />
                                        <x-text-input :id="`options_${index}_max`" class="form-control" type="number" step="any" :name="`options[${index}][max]`" x-model="option.max" />
                                        <x-input-error :messages="$errors->get(`options.${index}.max`)" class="mt-2" />
                                    </div>

                                    <div class="col-md-2">
                                        <x-input-label :for="`options_${index}_score`" :value="__('Skor')" />
                                        <x-text-input :id="`options_${index}_score`" class="form-control" type="number" :name="`options[${index}][score]`" x-model="option.score" required />
                                        <x-input-error :messages="$errors->get(`options.${index}.score`)" class="mt-2" />
                                    </div>

                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" @click="options.splice(index, 1)" class="btn btn-danger w-100">
                                            X
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <button type="button" @click="options.push({ value: '', min: '', max: '', score: '' })" class="btn btn-secondary mt-3">
                                {{ __('Tambah Opsi/Rentang') }}
                            </button>
                            <x-input-error :messages="$errors->get('options')" class="mt-2" />
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <x-primary-button>
                                {{ __('Simpan Parameter') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
