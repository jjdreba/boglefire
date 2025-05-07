import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { Fund } from '@/types/fund';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Trash2, RefreshCw } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';

interface Props {
    funds: Fund[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Funds',
        href: '/funds',
    },
];

export default function Index({ funds }: Props) {
    const [refreshing, setRefreshing] = useState<number | null>(null);

    const refreshPrice = (fundId: number) => {
        setRefreshing(fundId);
        router.post(
            route('funds.refresh-price', fundId),
            {},
            {
                onSuccess: () => setRefreshing(null),
                onError: () => setRefreshing(null),
            }
        );
    };

    const deleteFund = (fundId: number) => {
        router.delete(route('funds.destroy', fundId));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="My Funds" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex justify-between items-center mb-4">
                    <h1 className="text-2xl font-semibold">My Funds</h1>
                    <Button asChild>
                        <Link href={route('funds.create')}>Add New Fund</Link>
                    </Button>
                </div>

                {funds.length === 0 ? (
                    <div className="border-sidebar-border/70 dark:border-sidebar-border flex flex-col items-center justify-center p-12 rounded-xl border">
                        <h3 className="text-lg font-semibold">No funds yet</h3>
                        <p className="text-sm text-gray-500 mt-1 mb-4">Get started by adding a new fund to track.</p>
                        <Button asChild>
                            <Link href={route('funds.create')}>Add Fund</Link>
                        </Button>
                    </div>
                ) : (
                    <div className="border-sidebar-border/70 dark:border-sidebar-border overflow-hidden rounded-xl border">
                        <Table>
                            <TableCaption>A list of your funds</TableCaption>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Symbol</TableHead>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Type</TableHead>
                                    <TableHead>Exchange</TableHead>
                                    <TableHead>Last Price</TableHead>
                                    <TableHead>Updated</TableHead>
                                    <TableHead className="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {funds.map((fund) => (
                                    <TableRow key={fund.id}>
                                        <TableCell className="font-medium">{fund.symbol}</TableCell>
                                        <TableCell>{fund.name}</TableCell>
                                        <TableCell>{fund.type}</TableCell>
                                        <TableCell>{fund.exchange}</TableCell>
                                        <TableCell>{fund.last_price_formatted}</TableCell>
                                        <TableCell>{fund.last_price_updated_at}</TableCell>
                                        <TableCell className="text-right space-x-2">
                                            <Button
                                                variant="outline"
                                                size="icon"
                                                onClick={() => refreshPrice(fund.id)}
                                                disabled={refreshing === fund.id}
                                            >
                                                <RefreshCw className={`h-4 w-4 ${refreshing === fund.id ? 'animate-spin' : ''}`} />
                                            </Button>
                                            <AlertDialog>
                                                <AlertDialogTrigger asChild>
                                                    <Button variant="outline" size="icon">
                                                        <Trash2 className="h-4 w-4 text-red-500" />
                                                    </Button>
                                                </AlertDialogTrigger>
                                                <AlertDialogContent>
                                                    <AlertDialogHeader>
                                                        <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                                                        <AlertDialogDescription>
                                                            This will permanently delete the fund {fund.symbol} from your portfolio.
                                                        </AlertDialogDescription>
                                                    </AlertDialogHeader>
                                                    <AlertDialogFooter>
                                                        <AlertDialogCancel>Cancel</AlertDialogCancel>
                                                        <AlertDialogAction
                                                            onClick={() => deleteFund(fund.id)}
                                                            className="bg-red-600 hover:bg-red-700"
                                                        >
                                                            Delete
                                                        </AlertDialogAction>
                                                    </AlertDialogFooter>
                                                </AlertDialogContent>
                                            </AlertDialog>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    </div>
                )}
            </div>
        </AppLayout>
    );
} 