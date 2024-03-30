export const checkInputType = (input, showPassword) => {
    if (input.type === "password") {
        return showPassword ? "text" : "password";
    } else {
        return input.type;
    }
};
