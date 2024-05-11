import styles from "./SignupForm.module.scss";
import { Form, Button } from "react-bootstrap";
import { REGISTER_INPUTS, REGISTER_SCHEMA } from "@/utils/constants";
import { FormInput } from "@/components";
import { useState } from "react";
import { useForm, SubmitHandler } from "react-hook-form";
import { TRegisterData } from "@/utils/types";
import { zodResolver } from "@hookform/resolvers/zod";
import axios from "axios";
import Spinner from "react-bootstrap/Spinner";
import { useToastMessage } from "@/utils/hooks";

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
            const response = await axios.post(
                `${process.env.BASE_URL}/Users/RegisterUser`,
                registerData
            );

            switch (response.data.status) {
                case 200:
                    showToast("success", response.data.description);
                    break;

                case 403:
                    showToast("error", response.data.description);
                    break;
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
                <Button type="submit">
                    {isLoading ? (
                        <Spinner animation="border" size="sm" />
                    ) : (
                        "Submit"
                    )}
                </Button>
            </Form>
        </div>
    );
};

export default SignupForm;
