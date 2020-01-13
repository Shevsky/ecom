declare global {
	interface Window {
		shipping_ecom_public_path: string;
	}
}

export function getPublicPath(path: string): string {
	return window.shipping_ecom_public_path + path;
}
