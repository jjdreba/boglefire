import { usePage } from '@inertiajs/react';
import { toast } from 'sonner';
import { useEffect } from 'react';

interface Flash {
    success?: string;
    error?: string;
}

export function FlashMessages() {
    const { props } = usePage();
    const flash = props.flash as Flash | undefined;

    useEffect(() => {
        if (flash?.success) {
            toast.success(flash.success);
        }

        if (flash?.error) {
            toast.error(flash.error);
        }
    }, [flash?.success, flash?.error]);

    // This component doesn't render anything, it just shows toasts
    return null;
} 