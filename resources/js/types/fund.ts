export interface Fund {
    id: number;
    symbol: string;
    name: string;
    type: string;
    exchange: string | null;
    currency: string;
    last_price: number | null;
    last_price_updated_at: string | null;
    last_price_formatted: string | null;
    created_at: string;
    updated_at: string;
} 