import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import { FlashMessages } from '@/components/FlashMessages';
import { type BreadcrumbItem } from '@/types';
import { type ReactNode } from 'react';

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

export default ({ children, breadcrumbs, ...props }: AppLayoutProps) => (
    <AppLayoutTemplate breadcrumbs={breadcrumbs} {...props}>
        {/* Handle flash messages */}
        <FlashMessages />
        {children}
    </AppLayoutTemplate>
);
