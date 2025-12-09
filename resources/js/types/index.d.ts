import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Customer {
    id: number;
    identification_type: string;
    identification_type_label: string;
    identification_number: string;
    name: string;
    trade_name?: string;
    email?: string;
    phone?: string;
    mobile_phone?: string;
    country?: string;
    province?: string;
    city?: string;
    address?: string;
    created_at: string;
    updated_at: string;
}

export interface Product {
    id: number;
    sku: string;
    name: string;
    description: string;
    price: number;
    stock: number;
    created_at: string;
    updated_at: string;
}

export interface Vendor {
    id: number;
    name: string;
    email: string;
    phone: string;
    address: string;
    contact_name: string;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
