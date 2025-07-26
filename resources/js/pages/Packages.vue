<script lang="ts" setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

interface Package {
    id: number;
    name: string;
    price: number;
    speed: string;
    clients_count: number;
}

interface Props {
    packages: Package[];
}

const { packages } = defineProps<Props>();
const page = usePage();
const errorMessage = ref('');
const showError = ref(false);

onMounted(() => {
    const error = page.props.flash?.error;
    if (error) {
        errorMessage.value = error;
        showError.value = true;

        // Auto-hide after 5 seconds
        setTimeout(() => {
            showError.value = false;
        }, 5000);
    }
});

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Paket Internet',
        href: '/packages',
    },
];

const isAddModalOpen = ref(false);
const isEditModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const selectedPackage = ref<Package | null>(null);

const addForm = useForm({
    name: '',
    price: '',
    speed: '',
});

const editForm = useForm({
    name: '',
    price: '',
    speed: '',
});

const openAddModal = () => {
    addForm.reset();
    isAddModalOpen.value = true;
};

const openEditModal = (pkg: Package) => {
    selectedPackage.value = pkg;
    editForm.name = pkg.name;
    editForm.price = pkg.price.toString();
    editForm.speed = pkg.speed;
    isEditModalOpen.value = true;
};

const openDeleteModal = (pkg: Package) => {
    selectedPackage.value = pkg;
    isDeleteModalOpen.value = true;
};

const submitAddForm = () => {
    addForm.post(route('packages.store'), {
        onSuccess: () => {
            isAddModalOpen.value = false;
        },
    });
};

const submitEditForm = () => {
    if (!selectedPackage.value) return;

    editForm.patch(route('packages.update', selectedPackage.value.id), {
        onSuccess: () => {
            isEditModalOpen.value = false;
        },
    });
};

const deletePackage = () => {
    if (!selectedPackage.value) return;

    useForm({}).delete(route('packages.destroy', selectedPackage.value.id), {
        onSuccess: () => {
            isDeleteModalOpen.value = false;
        },
        onError: (errors) => {
            isDeleteModalOpen.value = false;
            // If there's an error from the server
            if (errors.message) {
                errorMessage.value = errors.message;
                showError.value = true;

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    showError.value = false;
                }, 5000);
            }
        },
    });
};

const closeError = () => {
    showError.value = false;
};

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    }).format(price);
};

const getSpeedColor = (speed: string): string => {
    const speedValue = parseInt(speed);
    if (isNaN(speedValue)) return 'bg-blue-100 text-blue-800 border-blue-200';

    if (speedValue >= 100) return 'bg-purple-100 text-purple-800 border-purple-200';
    if (speedValue >= 50) return 'bg-indigo-100 text-indigo-800 border-indigo-200';
    if (speedValue >= 20) return 'bg-green-100 text-green-800 border-green-200';
    return 'bg-amber-100 text-amber-800 border-amber-200';
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Paket" />

        <!-- Error Alert -->
        <div v-if="showError" class="fixed top-4 right-4 z-50 flex w-80 items-center rounded-md border border-red-200 bg-red-50 p-4 shadow-lg">
            <div class="mr-3 flex-shrink-0 text-red-600">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path
                        clip-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 01-1-1V8a1 1 0 112 0v6a1 1 0 01-1 1z"
                        fill-rule="evenodd"
                    ></path>
                </svg>
            </div>
            <div class="text-sm text-red-800">{{ errorMessage }}</div>
            <div class="ml-auto pl-3">
                <button
                    class="-mx-1.5 -my-1.5 inline-flex h-6 w-6 items-center justify-center rounded-md text-red-600 hover:bg-red-200 focus:outline-none"
                    @click="closeError"
                >
                    <span class="sr-only">Tutup</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <HeadingSmall description="Kelola paket internet yang tersedia" title="Paket Internet" />
                <Button class="gap-2" @click="openAddModal">
                    <svg fill="currentColor" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                    </svg>
                    Tambah Paket
                </Button>
            </div>

            <div class="rounded-md border bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b bg-muted/50 font-medium">
                            <th class="p-3 text-left">Nama</th>
                            <th class="p-3 text-left">Harga</th>
                            <th class="p-3 text-left">Kecepatan</th>
                            <th class="p-3 text-left">Jumlah Pelanggan</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="pkg in packages" :key="pkg.id" class="border-b transition-colors hover:bg-muted/20">
                            <td class="p-3 font-medium">{{ pkg.name }}</td>
                            <td class="p-3">
                                <span class="font-semibold text-primary">{{ formatPrice(pkg.price) }}</span>
                                <span class="text-xs text-muted-foreground">/bulan</span>
                            </td>
                            <td class="p-3">
                                <span :class="`rounded-full border px-2 py-1 text-xs ${getSpeedColor(pkg.speed)}`">
                                    {{ pkg.speed }}
                                </span>
                            </td>
                            <td class="p-3">
                                <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium"> {{ pkg.clients_count }} pelanggan </span>
                            </td>
                            <td class="p-3 text-right">
                                <div class="flex justify-end space-x-2">
                                    <Button class="gap-1" size="sm" variant="outline" @click="openEditModal(pkg)">
                                        <svg fill="currentColor" height="12" viewBox="0 0 16 16" width="12" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"
                                            />
                                        </svg>
                                        Ubah
                                    </Button>
                                    <Button class="gap-1" size="sm" variant="destructive" @click="openDeleteModal(pkg)">
                                        <svg fill="currentColor" height="12" viewBox="0 0 16 16" width="12" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"
                                            />
                                            <path
                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3h11V1h-11z"
                                                fill-rule="evenodd"
                                            />
                                        </svg>
                                        Hapus
                                    </Button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="packages.length === 0">
                            <td class="p-8 text-center text-muted-foreground" colspan="5">
                                Belum ada paket internet tersedia. Klik tombol "Tambah Paket" untuk menambahkan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Package Modal -->
        <Dialog v-model:open="isAddModalOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Tambah Paket Baru</DialogTitle>
                    <DialogDescription>Tambahkan paket internet baru ke sistem.</DialogDescription>
                </DialogHeader>

                <form class="space-y-4" @submit.prevent="submitAddForm">
                    <div class="grid gap-2">
                        <Label for="add-name">Nama Paket</Label>
                        <Input id="add-name" v-model="addForm.name" placeholder="Internet Dasar" required />
                        <InputError :message="addForm.errors.name" />
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="add-price">Harga (IDR)</Label>
                            <Input id="add-price" v-model="addForm.price" placeholder="150000" required type="number" />
                            <InputError :message="addForm.errors.price" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="add-speed">Kecepatan</Label>
                            <Input id="add-speed" v-model="addForm.speed" placeholder="10 Mbps" required />
                            <InputError :message="addForm.errors.speed" />
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
                            Buat Paket
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Edit Package Modal -->
        <Dialog v-model:open="isEditModalOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Ubah Paket</DialogTitle>
                    <DialogDescription>Perbarui informasi paket internet.</DialogDescription>
                </DialogHeader>

                <form class="space-y-4" @submit.prevent="submitEditForm">
                    <div class="grid gap-2">
                        <Label for="edit-name">Nama Paket</Label>
                        <Input id="edit-name" v-model="editForm.name" required />
                        <InputError :message="editForm.errors.name" />
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="edit-price">Harga (IDR)</Label>
                            <Input id="edit-price" v-model="editForm.price" required type="number" />
                            <InputError :message="editForm.errors.price" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="edit-speed">Kecepatan</Label>
                            <Input id="edit-speed" v-model="editForm.speed" required />
                            <InputError :message="editForm.errors.speed" />
                        </div>
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
                            Perbarui Paket
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Delete Package Modal -->
        <Dialog v-model:open="isDeleteModalOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Hapus Paket</DialogTitle>
                    <DialogDescription>
                        <div v-if="selectedPackage?.clients_count && selectedPackage.clients_count > 0" class="mb-2 text-red-500">
                            Paket ini digunakan oleh {{ selectedPackage.clients_count }} pelanggan. Ubah paket pelanggan terlebih dahulu sebelum
                            menghapus.
                        </div>
                        <div v-else>Apakah Anda yakin ingin menghapus "{{ selectedPackage?.name }}"? Tindakan ini tidak dapat dibatalkan.</div>
                    </DialogDescription>
                </DialogHeader>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="isDeleteModalOpen = false">Batal</Button>
                    <Button
                        :disabled="selectedPackage?.clients_count && selectedPackage.clients_count > 0"
                        class="gap-1"
                        type="button"
                        variant="destructive"
                        @click="deletePackage"
                    >
                        <svg fill="currentColor" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"
                            />
                            <path
                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3h11V1h-11z"
                                fill-rule="evenodd"
                            />
                        </svg>
                        Hapus Paket
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
