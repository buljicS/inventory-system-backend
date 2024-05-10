import {
    FormSelectInput,
    FormNumberInput,
    FormTextareaInput,
    FormPasswordInput,
    FormTextInput,
} from "@/components";

const FormInput = ({ input, errors, register }) => {
    const INPUT_COMPONENTS = {
        select: FormSelectInput,
        email: FormTextInput,
        text: FormTextInput,
        password: FormPasswordInput,
        number: FormNumberInput,
        textarea: FormTextareaInput,
    };
    const InputComponent = INPUT_COMPONENTS[input.type];
    return <InputComponent input={input} errors={errors} register={register} />;
};

export default FormInput;
