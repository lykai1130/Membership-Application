<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member Registration</title>
    <style>
        :root {
            --bg-top: #f1f5f9;
            --bg-bottom: #dbeafe;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --line: #d1d5db;
            --primary: #1d4ed8;
            --primary-dark: #1e40af;
            --secondary: #ffffff;
            --danger: #dc2626;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(140deg, var(--bg-top), var(--bg-bottom));
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            width: min(100%, 700px);
            background: var(--card);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.14);
        }

        h1 {
            margin: 0 0 8px;
            font-size: 1.5rem;
        }

        p {
            margin: 0 0 20px;
            color: var(--muted);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .section-title {
            margin: 16px 0 8px;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .address-card {
            margin-top: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px;
            background: #f8fbff;
        }

        .address-actions {
            display: flex;
            justify-content: flex-end;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 0.9rem;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.95rem;
            background: #fff;
            color: var(--text);
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.14);
        }

        input.is-invalid,
        select.is-invalid {
            border-color: var(--danger);
        }

        input.is-invalid:focus,
        select.is-invalid:focus {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.14);
        }

        .actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            border: 1px solid var(--primary);
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-primary:disabled {
            background: #93c5fd;
            border-color: #93c5fd;
            cursor: not-allowed;
        }

        .btn-secondary {
            background: var(--secondary);
            color: var(--primary);
        }

        .btn-secondary:hover {
            background: #eff6ff;
        }

        .btn-danger {
            border-color: #dc2626;
            color: #dc2626;
            background: #fff;
        }

        .btn-danger:hover {
            background: #fef2f2;
        }

        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Member Registration</h1>
        <p></p>

        @if (session('success'))
            <p style="margin:0 0 14px;color:#166534;font-weight:600;">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <p style="margin:0 0 14px;color:#b91c1c;font-weight:600;">{{ $errors->first() }}</p>
        @endif

        <form id="registration-form" method="POST" action="{{ url('/registration') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid">
                <div class="field full">
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" data-required="true" required>
                </div>

                <div class="field full">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" data-required="true" required>
                </div>

                <div class="field">
                    <label for="phone">Phone</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" data-required="true" required>
                </div>

                <div class="field">
                    <label for="dob">Date of Birth</label>
                    <input id="dob" type="date" name="dob" value="{{ old('dob') }}" data-required="true" required>
                </div>

                <div class="field">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" data-required="true" required>
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="field">
                    <label for="referral_code">Referral Code (optional)</label>
                    <input id="referral_code" type="text" name="referral_code" maxlength="6" value="{{ old('referral_code') }}">
                </div>

                <div class="field">
                    <label for="avatar_image">Avatar Image</label>
                    <input id="avatar_image" type="file" name="avatar_image" accept="image/*">
                </div>

                <div class="field">
                    <label for="proof_of_address">Proof Of Address Document</label>
                    <input id="proof_of_address" type="file" name="proof_of_address">
                </div>

            </div>

            @if (!empty($addressTypes) && $addressTypes->count())
                <h2 class="section-title">Addresses</h2>
                @php
                    $oldAddresses = old('addresses');
                    if (!is_array($oldAddresses) || count($oldAddresses) === 0) {
                        $oldAddresses = [[
                            'address_type_id' => '',
                            'line1' => '',
                            'line2' => '',
                            'city' => '',
                            'state' => '',
                            'postal_code' => '',
                            'country' => '',
                        ]];
                    }
                    $oldAddresses = array_values(array_slice($oldAddresses, 0, 2));
                @endphp

                <div id="addresses-container">
                    @foreach ($oldAddresses as $index => $address)
                        <div class="grid address-card" data-address-index="{{ $index }}">
                            <div class="field full">
                                <label for="address_{{ $index }}_address_type_id">Address Type</label>
                                <select id="address_{{ $index }}_address_type_id" name="addresses[{{ $index }}][address_type_id]" data-required="true" required>
                                    <option value="">Select Address Type</option>
                                    @foreach ($addressTypes as $addressType)
                                        <option value="{{ $addressType->id }}" {{ (string) ($address['address_type_id'] ?? '') === (string) $addressType->id ? 'selected' : '' }}>
                                            {{ $addressType->type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field full">
                                <label for="address_{{ $index }}_line1">Address Line 1</label>
                                <input id="address_{{ $index }}_line1" type="text" name="addresses[{{ $index }}][line1]" value="{{ $address['line1'] ?? '' }}" data-required="true" required>
                            </div>

                            <div class="field full">
                                <label for="address_{{ $index }}_line2">Address Line 2 (optional)</label>
                                <input id="address_{{ $index }}_line2" type="text" name="addresses[{{ $index }}][line2]" value="{{ $address['line2'] ?? '' }}">
                            </div>

                            <div class="field">
                                <label for="address_{{ $index }}_city">City</label>
                                <input id="address_{{ $index }}_city" type="text" name="addresses[{{ $index }}][city]" value="{{ $address['city'] ?? '' }}" data-required="true" required>
                            </div>

                            <div class="field">
                                <label for="address_{{ $index }}_state">State</label>
                                <input id="address_{{ $index }}_state" type="text" name="addresses[{{ $index }}][state]" value="{{ $address['state'] ?? '' }}" data-required="true" required>
                            </div>

                            <div class="field">
                                <label for="address_{{ $index }}_postal_code">Postal Code</label>
                                <input id="address_{{ $index }}_postal_code" type="text" name="addresses[{{ $index }}][postal_code]" value="{{ $address['postal_code'] ?? '' }}" data-required="true" required>
                            </div>

                            <div class="field">
                                <label for="address_{{ $index }}_country">Country</label>
                                <input id="address_{{ $index }}_country" type="text" name="addresses[{{ $index }}][country]" value="{{ $address['country'] ?? '' }}" data-required="true" required>
                            </div>

                            <div class="field full address-actions">
                                <button type="button" class="btn btn-danger remove-address-btn">Remove Address</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="actions">
                <button id="add-address-btn" class="btn btn-secondary" type="button">Add Another Address</button>
                <button id="register-btn" class="btn btn-primary" type="submit">Register</button>
                <a class="btn btn-secondary" href="{{ url('/') }}">Back To Menu</a>
            </div>
        </form>
    </div>
    <script>
        const form = document.getElementById('registration-form');
        const registerButton = document.getElementById('register-btn');
        const addressesContainer = document.getElementById('addresses-container');
        const addAddressButton = document.getElementById('add-address-btn');
        const addressTypes = @json(($addressTypes ?? collect())->map(fn ($type) => ['id' => $type->id, 'type' => $type->type])->values());

        function isEmpty(field) {
            return (field.value || '').trim() === '';
        }

        function validateField(field) {
            field.classList.toggle('is-invalid', isEmpty(field));
        }

        function getRequiredFields() {
            return Array.from(document.querySelectorAll('[data-required="true"]'));
        }

        function bindValidation(field) {
            if (field.dataset.validationBound === '1') {
                return;
            }

            const eventName = field.tagName === 'SELECT' ? 'change' : 'input';
            field.addEventListener(eventName, () => {
                validateField(field);
                updateRegisterButtonState();
            });
            field.addEventListener('blur', () => validateField(field));
            field.dataset.validationBound = '1';
        }

        function bindAllValidation() {
            getRequiredFields().forEach(bindValidation);
        }

        function getAddressBlockCount() {
            if (!addressesContainer) {
                return 0;
            }
            return addressesContainer.querySelectorAll('[data-address-index]').length;
        }

        function updateAddButtonState() {
            if (!addAddressButton || !addressesContainer) {
                return;
            }
            addAddressButton.disabled = getAddressBlockCount() >= 2;
        }

        function updateRemoveButtons() {
            if (!addressesContainer) {
                return;
            }

            const blocks = addressesContainer.querySelectorAll('[data-address-index]');
            const showRemove = blocks.length > 1;
            blocks.forEach((block) => {
                const button = block.querySelector('.remove-address-btn');
                if (button) {
                    button.style.display = showRemove ? 'inline-block' : 'none';
                }
            });
        }

        function reindexAddressBlocks() {
            if (!addressesContainer) {
                return;
            }

            const blocks = addressesContainer.querySelectorAll('[data-address-index]');
            blocks.forEach((block, index) => {
                block.dataset.addressIndex = String(index);

                const labels = block.querySelectorAll('label[for]');
                labels.forEach((label) => {
                    label.htmlFor = label.htmlFor.replace(/address_\d+_/g, `address_${index}_`);
                });

                const inputs = block.querySelectorAll('input, select');
                inputs.forEach((input) => {
                    if (input.id) {
                        input.id = input.id.replace(/address_\d+_/g, `address_${index}_`);
                    }
                    if (input.name) {
                        input.name = input.name.replace(/addresses\[\d+\]/g, `addresses[${index}]`);
                    }
                });
            });
        }

        function buildAddressBlock(index) {
            const options = addressTypes.map((type) => {
                return `<option value="${type.id}">${type.type}</option>`;
            }).join('');

            return `
                <div class="grid address-card" data-address-index="${index}">
                    <div class="field full">
                        <label for="address_${index}_address_type_id">Address Type</label>
                        <select id="address_${index}_address_type_id" name="addresses[${index}][address_type_id]" data-required="true" required>
                            <option value="">Select Address Type</option>
                            ${options}
                        </select>
                    </div>

                    <div class="field full">
                        <label for="address_${index}_line1">Address Line 1</label>
                        <input id="address_${index}_line1" type="text" name="addresses[${index}][line1]" data-required="true" required>
                    </div>

                    <div class="field full">
                        <label for="address_${index}_line2">Address Line 2 (optional)</label>
                        <input id="address_${index}_line2" type="text" name="addresses[${index}][line2]">
                    </div>

                    <div class="field">
                        <label for="address_${index}_city">City</label>
                        <input id="address_${index}_city" type="text" name="addresses[${index}][city]" data-required="true" required>
                    </div>

                    <div class="field">
                        <label for="address_${index}_state">State</label>
                        <input id="address_${index}_state" type="text" name="addresses[${index}][state]" data-required="true" required>
                    </div>

                    <div class="field">
                        <label for="address_${index}_postal_code">Postal Code</label>
                        <input id="address_${index}_postal_code" type="text" name="addresses[${index}][postal_code]" data-required="true" required>
                    </div>

                    <div class="field">
                        <label for="address_${index}_country">Country</label>
                        <input id="address_${index}_country" type="text" name="addresses[${index}][country]" data-required="true" required>
                    </div>

                    <div class="field full address-actions">
                        <button type="button" class="btn btn-danger remove-address-btn">Remove Address</button>
                    </div>
                </div>
            `;
        }

        function updateRegisterButtonState() {
            const hasEmptyRequired = getRequiredFields().some(isEmpty);
            registerButton.disabled = hasEmptyRequired;
        }

        if (addAddressButton && addressesContainer) {
            addAddressButton.addEventListener('click', () => {
                if (getAddressBlockCount() >= 2) {
                    return;
                }

                const nextIndex = getAddressBlockCount();
                addressesContainer.insertAdjacentHTML('beforeend', buildAddressBlock(nextIndex));
                bindAllValidation();
                updateRemoveButtons();
                updateAddButtonState();
                updateRegisterButtonState();
            });

            addressesContainer.addEventListener('click', (event) => {
                const target = event.target;
                if (!(target instanceof HTMLElement) || !target.classList.contains('remove-address-btn')) {
                    return;
                }

                const block = target.closest('[data-address-index]');
                if (!block) {
                    return;
                }

                block.remove();
                reindexAddressBlocks();
                bindAllValidation();
                updateRemoveButtons();
                updateAddButtonState();
                updateRegisterButtonState();
            });
        }

        form.addEventListener('submit', (event) => {
            getRequiredFields().forEach(validateField);
            updateRegisterButtonState();

            if (registerButton.disabled) {
                event.preventDefault();
            }
        });

        bindAllValidation();
        reindexAddressBlocks();
        updateRemoveButtons();
        updateAddButtonState();
        updateRegisterButtonState();
    </script>
</body>
</html>
