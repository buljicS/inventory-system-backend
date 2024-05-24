import styles from "./SignupForm.module.scss";
import { Form } from "react-bootstrap";
import {
    API_ENDPOINT,
    REGISTER_INPUTS,
    REGISTER_SCHEMA,
} from "@/utils/constants";
import { FormInput, FormSubmit } from "@/components";
import { useState } from "react";
import { useForm, SubmitHandler } from "react-hook-form";
import { TRegisterData } from "@/utils/types";
import { zodResolver } from "@hookform/resolvers/zod";
import { useToastMessage } from "@/utils/hooks";
import axiosInstance from "@/utils/axiosInstance";

const SignupForm = () => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const showToast = useToastMessage();
    const {
        register,
        handleSubmit,
        reset,
        formState: { errors },
    } = useForm<TRegisterData>({ resolver: zodResolver(REGISTER_SCHEMA) });

    const onSubmit: SubmitHandler<TRegisterData> = async (data) => {
        try {
            setIsLoading(true);
            const { repeatPassword, ...registerData } = data;
            const response = await axiosInstance.post(
                `${process.env.BASE_URL}${API_ENDPOINT.REGISTER}`,
                registerData
            );

            switch (response.data.status) {
                case 200:
                    showToast("success", response.data.description);
                    break;

                case 403:
                    showToast("error", response.data.description);
                    break;
                default:
                    setIsLoading(false);
            }
        } catch (error) {
            console.log(error);
        } finally {
            setIsLoading(false);
        }
    };
    return (
        <div className={styles.form}>
            <Form onSubmit={handleSubmit(onSubmit)}>
                {REGISTER_INPUTS.map((input) => (
                    <FormInput
                        input={input}
                        register={register}
                        errors={errors}
                        key={input.id}
                    />
                ))}
                <FormSubmit isLoading={isLoading} value="Submit" />
            </Form>
        </div>
    );
};

export default SignupForm;
