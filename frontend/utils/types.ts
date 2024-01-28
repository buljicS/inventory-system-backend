export type TRegisterLoginInputs = {
    id: number;
    element: string;
    label: string;
    name: string;
    type?: string;
    placeholder: string;
    required: boolean;
};

export type TRegisterData = {
    fname: string;
    lname: string;
    email: string;
    password: string;
    repeatPassword: string;
    phoneNumber: string;
    company: string;
};

export type TLoginData = {
    email: string;
    password: string;
};
