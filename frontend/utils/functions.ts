import { TLoginData, TRegisterData } from "./types";

export const validateLoginForm = (values: TLoginData) => {
    const emailPattern = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/;
    const passwordPattern =
        /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;

    const isValid =
        emailPattern.test(values.email) &&
        passwordPattern.test(values.password);

    const errorMessages = {
        email: !emailPattern.test(values.email)
            ? "Please enter a valid email address."
            : "",
        password: !passwordPattern.test(values.password)
            ? "Your password must contain minimum 8 characters, at least one uppercase letter, one lowercase letter, one number and one special character."
            : "",
    };

    return { isValid, errorMessages };
};

export const validateRegisterForm = (values: TRegisterData) => {
    const emailPattern = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/;
    const passwordPattern =
        /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
    const namePattern = /^.{3,}$/;
    const phoneNumberPattern = /^(0|\+381)[1-9]\d{8}$/;

    const isValid =
        namePattern.test(values.fname) &&
        namePattern.test(values.lname) &&
        emailPattern.test(values.email) &&
        passwordPattern.test(values.password) &&
        values.password === values.repeatPassword &&
        phoneNumberPattern.test(values.phoneNumber);

    const errorMessages = {
        fname: !namePattern.test(values?.fname)
            ? "Your first name must contain at least 3 characters."
            : "",
        lname: !namePattern.test(values?.lname)
            ? "Your last name must contain at least 3 characters."
            : "",
        email: !emailPattern.test(values?.email)
            ? "Please enter a valid email address."
            : "",
        password: !passwordPattern.test(values?.password)
            ? "Your password must contain minimum 8 characters, at least one uppercase letter, one lowercase letter, one number and one special character."
            : "",
        repeatPassword:
            values?.password !== values?.repeatPassword
                ? "Password do not match."
                : "",
        phoneNumber: !phoneNumberPattern.test(values?.phoneNumber)
            ? "Your phone number must be in this format: 06********"
            : "",
    };

    return { isValid, errorMessages };
};
