import { User } from "./user";

export interface NavItem {
    title: string;
    href: string;
    icon?: any;
    disabled?: boolean;
    external?: boolean;
}

export interface BreadcrumbItem {
    title: string;
    href?: string;
}

export interface SharedData {
    name: string;
    quote: {
        message: string;
        author: string;
    };
    auth: {
        user: User | null;
    };
    ziggy: {
        location: string;
    };
    sidebarOpen: boolean;
    flash: {
        success?: string;
        error?: string;
    };
} 