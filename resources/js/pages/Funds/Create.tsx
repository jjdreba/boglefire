import { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { ArrowLeft } from 'lucide-react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Funds',
        href: '/funds',
    },
    {
        title: 'Add Fund',
        href: '/funds/create',
    },
];

export default function Create() {
    const [searching, setSearching] = useState(false);
    const { data, setData, post, processing, errors } = useForm({
        symbol: '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        setSearching(true);
        post(route('funds.store'), {
            onSuccess: () => {
                setSearching(false);
            },
            onError: () => {
                setSearching(false);
            },
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Add New Fund" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex items-center gap-2 mb-4">
                    <Button variant="ghost" size="icon" asChild className="flex items-center gap-1">
                        <Link href={route('funds.index')}>
                            <ArrowLeft className="h-4 w-4" />
                        </Link>
                    </Button>
                    <h1 className="text-2xl font-semibold">Add New Fund</h1>
                </div>

                <div className="border-sidebar-border/70 dark:border-sidebar-border overflow-hidden rounded-xl border">
                    <Card className="border-none shadow-none">
                        <CardHeader>
                            <CardTitle>Fund Details</CardTitle>
                            <CardDescription>
                                Enter the ticker symbol for a stock, ETF, mutual fund, or other asset you want to track.
                            </CardDescription>
                        </CardHeader>
                        <form onSubmit={submit}>
                            <CardContent>
                                <div className="grid w-full items-center gap-4">
                                    <div className="flex flex-col space-y-1.5">
                                        <Label htmlFor="symbol">Symbol</Label>
                                        <Input
                                            id="symbol"
                                            name="symbol"
                                            placeholder="e.g., VOO, AAPL, BRK.B"
                                            value={data.symbol}
                                            onChange={(e) => setData('symbol', e.target.value)}
                                            autoFocus
                                            disabled={processing}
                                        />
                                        {errors.symbol && <p className="text-sm font-medium text-red-500">{errors.symbol}</p>}
                                    </div>
                                </div>
                            </CardContent>
                            <CardFooter className="flex justify-between">
                                <Button variant="outline" asChild>
                                    <Link href={route('funds.index')}>Cancel</Link>
                                </Button>
                                <Button type="submit" disabled={processing || !data.symbol}>
                                    {searching ? 'Searching...' : 'Add Fund'}
                                </Button>
                            </CardFooter>
                        </form>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
} 