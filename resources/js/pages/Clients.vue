<script lang="ts" setup>
import { Head, useForm } from '@inertiajs/vue3';
import { onBeforeUnmount, ref } from 'vue';

import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface Client {
    id: number;
    name: string;
    address: string;
    whatsapp_number: string;
    id_card_photo_path: string | null;
    package_id: number;
    package?: Package;
}

interface Package {
    id: number;
    name: string;
    price: number;
    speed: string;
}

interface Props {
    clients: Client[];
    packages: Package[];
}

const { clients, packages } = defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Pelanggan',
        href: '/clients',
    },
];

const isAddModalOpen = ref(false);
const isEditModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const selectedClient = ref<Client | null>(null);
const selectedPackage = ref<Package | null>(null);
const isDropdownOpen = ref(false);
const addImagePreview = ref<string | null>(null);
const editImagePreview = ref<string | null>(null);

const addForm = useForm({
    name: '',
    address: '',
    whatsapp_number: '',
    id_card_photo: null as File | null,
    package_id: '',
});

const editForm = useForm({
    name: '',
    address: '',
    whatsapp_number: '',
    id_card_photo: null as File | null,
    package_id: '',
    _method: 'PATCH',
});

const openAddModal = () => {
    addForm.reset();
    selectedPackage.value = null;
    addImagePreview.value = null;
    isAddModalOpen.value = true;
};

const openEditModal = (client: Client) => {
    selectedClient.value = client;
    editForm.name = client.name;
    editForm.address = client.address;
    editForm.whatsapp_number = client.whatsapp_number;
    editForm.id_card_photo = null;
    editForm.package_id = client.package_id.toString();
    selectedPackage.value = packages.find((p) => p.id === client.package_id) || null;
    editImagePreview.value = client.id_card_photo_path ? `/storage/${client.id_card_photo_path}` : null;
    isEditModalOpen.value = true;
};

const openDeleteModal = (client: Client) => {
    selectedClient.value = client;
    isDeleteModalOpen.value = true;
};

const selectPackage = (pkg: Package, isAdd: boolean) => {
    selectedPackage.value = pkg;
    if (isAdd) {
        addForm.package_id = pkg.id.toString();
    } else {
        editForm.package_id = pkg.id.toString();
    }
    isDropdownOpen.value = false;
};

const handleFileUpload = (e: Event, isAdd: boolean) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        const file = target.files[0];

        // Revoke previous object URL to prevent memory leaks
        if (isAdd && addImagePreview.value) {
            URL.revokeObjectURL(addImagePreview.value);
        } else if (!isAdd && editImagePreview.value && editImagePreview.value.startsWith('blob:')) {
            URL.revokeObjectURL(editImagePreview.value);
        }

        // Create and set new preview
        const previewUrl = URL.createObjectURL(file);

        if (isAdd) {
            addForm.id_card_photo = file;
            addImagePreview.value = previewUrl;
        } else {
            editForm.id_card_photo = file;
            editImagePreview.value = previewUrl;
        }
    }
};

const submitAddForm = () => {
    addForm.post(route('clients.store'), {
        onSuccess: () => {
            isAddModalOpen.value = false;
            if (addImagePreview.value) {
                URL.revokeObjectURL(addImagePreview.value);
                addImagePreview.value = null;
            }
        },
        forceFormData: true,
    });
};

const submitEditForm = () => {
    if (!selectedClient.value) return;

    editForm.post(route('clients.update', selectedClient.value.id), {
        onSuccess: () => {
            isEditModalOpen.value = false;
            if (editImagePreview.value && editImagePreview.value.startsWith('blob:')) {
                URL.revokeObjectURL(editImagePreview.value);
                editImagePreview.value = null;
            }
        },
        forceFormData: true,
    });
};

const deleteClient = () => {
    if (!selectedClient.value) return;

    useForm({}).delete(route('clients.destroy', selectedClient.value.id), {
        onSuccess: () => {
            isDeleteModalOpen.value = false;
        },
    });
};

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    }).format(price);
};

// Clean up any object URLs when component is unmounted
onBeforeUnmount(() => {
    if (addImagePreview.value) URL.revokeObjectURL(addImagePreview.value);
    if (editImagePreview.value && editImagePreview.value.startsWith('blob:')) {
        URL.revokeObjectURL(editImagePreview.value);
    }
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Pelanggan" />

        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <HeadingSmall description="Kelola data pelanggan internet" title="Pelanggan" />
                <Button class="gap-2" @click="openAddModal">
                    <svg class="bi bi-person-plus" fill="currentColor" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664z"
                        />
                        <path
                            d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"
                            fill-rule="evenodd"
                        />
                    </svg>
                    Tambah Pelanggan
                </Button>
            </div>

            <div class="rounded-md border bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b bg-muted/50 font-medium">
                            <th class="p-3 text-left">Nama</th>
                            <th class="p-3 text-left">Alamat</th>
                            <th class="p-3 text-left">WhatsApp</th>
                            <th class="p-3 text-left">Paket</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="client in clients" :key="client.id" class="border-b transition-colors hover:bg-muted/20">
                            <td class="p-3">{{ client.name }}</td>
                            <td class="p-3">{{ client.address }}</td>
                            <td class="p-3">
                                <a
                                    :href="`https://wa.me/${client.whatsapp_number}`"
                                    class="flex items-center gap-1 hover:text-primary"
                                    target="_blank"
                                >
                                    <svg fill="currentColor" height="12" viewBox="0 0 16 16" width="12" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"
                                        />
                                    </svg>
                                    {{ client.whatsapp_number }}
                                </a>
                            </td>
                            <td class="p-3">
                                <span class="rounded-full border border-primary/30 bg-primary/10 px-2 py-1 text-xs text-primary">
                                    {{ client.package?.name }} ({{ client.package?.speed }})
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <div class="flex justify-end space-x-2">
                                    <Button class="gap-1" size="sm" variant="outline" @click="openEditModal(client)">
                                        <svg fill="currentColor" height="12" viewBox="0 0 16 16" width="12" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
                                            />
                                            <path
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"
                                                fill-rule="evenodd"
                                            />
                                        </svg>
                                        Ubah
                                    </Button>
                                    <Button class="gap-1" size="sm" variant="destructive" @click="openDeleteModal(client)">
                                        <svg fill="currentColor" height="12" viewBox="0 0 16 16" width="12" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"
                                            />
                                            <path
                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"
                                            />
                                        </svg>
                                        Hapus
                                    </Button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="clients.length === 0">
                            <td class="p-8 text-center text-muted-foreground" colspan="5">
                                Belum ada data klien. Klik tombol "Tambah Pelanggan" untuk menambahkan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Client Modal -->
        <Dialog v-model:open="isAddModalOpen">
            <DialogContent class="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Tambah Pelanggan Baru</DialogTitle>
                    <DialogDescription>Tambahkan data pelanggan baru ke sistem.</DialogDescription>
                </DialogHeader>

                <form class="space-y-4" @submit.prevent="submitAddForm">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="add-name">Nama</Label>
                            <Input id="add-name" v-model="addForm.name" placeholder="John Doe" required />
                            <InputError :message="addForm.errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="add-whatsapp">Nomor WhatsApp</Label>
                            <Input id="add-whatsapp" v-model="addForm.whatsapp_number" placeholder="628123456789" required />
                            <InputError :message="addForm.errors.whatsapp_number" />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="add-address">Alamat</Label>
                        <Input id="add-address" v-model="addForm.address" placeholder="Jl. Example No. 123" required />
                        <InputError :message="addForm.errors.address" />
                    </div>

                    <div class="grid gap-2">
                        <Label>Paket Internet</Label>
                        <DropdownMenu v-model:open="isDropdownOpen">
                            <DropdownMenuTrigger asChild>
                                <Button class="w-full justify-between" variant="outline">
                                    <span>{{ selectedPackage?.name || 'Pilih Paket' }}</span>
                                    <svg fill="currentColor" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"
                                        />
                                    </svg>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="max-h-[200px] w-full overflow-auto">
                                <DropdownMenuItem
                                    v-for="pkg in packages"
                                    :key="pkg.id"
                                    class="flex justify-between"
                                    @click="selectPackage(pkg, true)"
                                >
                                    <span>{{ pkg.name }}</span>
                                    <span class="text-sm text-muted-foreground">{{ pkg.speed }} - {{ formatPrice(pkg.price) }}</span>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                        <InputError :message="addForm.errors.package_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="add-id-card">Foto KTP</Label>
                        <Input id="add-id-card" accept="image/*" class="cursor-pointer" type="file" @change="(e) => handleFileUpload(e, true)" />
                        <InputError :message="addForm.errors.id_card_photo" />

                        <div v-if="addImagePreview" class="mt-2 rounded-md border p-2">
                            <p class="mb-1 text-xs text-muted-foreground">Preview:</p>
                            <img :src="addImagePreview" alt="Preview KTP" class="mx-auto max-h-[200px] rounded" />
                        </div>
                    </div>

                    <DialogFooter>
                        <Button type="button" variant="outline" @click="isAddModalOpen = false">Batal</Button>
                        <Button :disabled="addForm.processing" class="gap-1" type="submit">
                            <span v-if="addForm.processing">
                                <svg class="h-4 w-4 animate-spin text-current" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path
                                        class="opacity-75"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                        fill="currentColor"
                                    ></path>
                                </svg>
                            </span>
                            Tambah Pelanggan
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Edit Client Modal -->
        <Dialog v-model:open="isEditModalOpen">
            <DialogContent class="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Ubah Data Pelanggan</DialogTitle>
                    <DialogDescription>Perbarui informasi pelanggan.</DialogDescription>
                </DialogHeader>

                <form class="space-y-4" @submit.prevent="submitEditForm">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="edit-name">Nama</Label>
                            <Input id="edit-name" v-model="editForm.name" required />
                            <InputError :message="editForm.errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="edit-whatsapp">Nomor WhatsApp</Label>
                            <Input id="edit-whatsapp" v-model="editForm.whatsapp_number" required />
                            <InputError :message="editForm.errors.whatsapp_number" />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="edit-address">Alamat</Label>
                        <Input id="edit-address" v-model="editForm.address" required />
                        <InputError :message="editForm.errors.address" />
                    </div>

                    <div class="grid gap-2">
                        <Label>Paket Internet</Label>
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button class="w-full justify-between" variant="outline">
                                    <span>{{ selectedPackage?.name || 'Pilih Paket' }}</span>
                                    <svg fill="currentColor" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"
                                        />
                                    </svg>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="max-h-[200px] w-full overflow-auto">
                                <DropdownMenuItem
                                    v-for="pkg in packages"
                                    :key="pkg.id"
                                    class="flex justify-between"
                                    @click="selectPackage(pkg, false)"
                                >
                                    <span>{{ pkg.name }}</span>
                                    <span class="text-sm text-muted-foreground">{{ pkg.speed }} - {{ formatPrice(pkg.price) }}</span>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                        <InputError :message="editForm.errors.package_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="edit-id-card">Foto KTP</Label>
                        <Input id="edit-id-card" accept="image/*" class="cursor-pointer" type="file" @change="(e) => handleFileUpload(e, false)" />
                        <InputError :message="editForm.errors.id_card_photo" />

                        <div v-if="editImagePreview" class="mt-2 rounded-md border p-2">
                            <p class="mb-1 text-xs text-muted-foreground">
                                {{ editForm.id_card_photo ? 'Preview gambar baru:' : 'Foto KTP tersimpan:' }}
                            </p>
                            <img :src="editImagePreview" alt="KTP" class="mx-auto max-h-[200px] rounded" />
                        </div>

                        <p v-if="selectedClient?.id_card_photo_path && editForm.id_card_photo" class="text-xs text-muted-foreground">
                            Upload baru akan mengganti foto KTP yang sudah ada.
                        </p>
                        <p v-if="!editImagePreview" class="text-xs text-muted-foreground">Belum ada foto KTP tersimpan. Silakan upload foto KTP.</p>
                    </div>

                    <DialogFooter>
                        <Button type="button" variant="outline" @click="isEditModalOpen = false">Batal</Button>
                        <Button :disabled="editForm.processing" class="gap-1" type="submit">
                            <span v-if="editForm.processing">
                                <svg class="h-4 w-4 animate-spin text-current" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path
                                        class="opacity-75"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                        fill="currentColor"
                                    ></path>
                                </svg>
                            </span>
                            Perbarui Pelanggan
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Delete Client Modal -->
        <Dialog v-model:open="isDeleteModalOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Hapus Pelanggan</DialogTitle>
                    <DialogDescription>
                        Apakah Anda yakin ingin menghapus "{{ selectedClient?.name }}"? Tindakan ini tidak dapat dibatalkan.
                    </DialogDescription>
                </DialogHeader>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="isDeleteModalOpen = false">Batal</Button>
                    <Button class="gap-1" type="button" variant="destructive" @click="deleteClient">
                        <svg fill="currentColor" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"
                            />
                            <path
                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"
                            />
                        </svg>
                        Hapus Pelanggan
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
