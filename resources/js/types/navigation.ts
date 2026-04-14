export type BreadcrumbItem = {
    title: string;
    href?: string;
};

export type NavItem = {
    title: string;
    url: null | string;
    icon: null | string;
    class: null | string;
    children: NavItem[];
};
