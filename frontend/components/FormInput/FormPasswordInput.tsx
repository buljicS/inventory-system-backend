import { Form } from "react-bootstrap";
import styles from "./FormInput.module.scss";
import Image from "next/image";
import { Tooltip, useDisclosure } from "@chakra-ui/react";
import {
    ErrorIcon,
    ShowPasswordIcon,
    HidePasswordIcon,
} from "@/resources/icons";
import { useState } from "react";

const FormPasswordInput = ({ input, errors, register }) => {
    const [showPassword, setShowPassword] = useState<boolean>(false);
    const { isOpen, onOpen, onToggle, onClose } = useDisclosure();

    const checkInputPassword =
        [
            "password",
            "repeatPassword",
            "newPassword",
            "repeatNewPassword",
            "currentPassword",
        ].includes(input.name) && errors[input.name];

    return (
        <Form.Group
            className="mb-3"
            controlId={input.name + input.id}
            key={input.id}
        >
            <Form.Label>{input.label}</Form.Label>
            <div className={styles.input}>
                <Form.Control
                    type={showPassword ? "text" : "password"}
                    placeholder={input.placeholder}
                    // @ts-ignore */
                    {...register(input.name)}
                    maxLength={50}
                    autoComplete="true"
                />
                <div
                    className={styles.input_toggle_button}
                    onClick={() => {
                        setShowPassword((prev) => !prev);
                    }}
                    style={{
                        right: checkInputPassword ? "55px" : "15px",
                    }}
                >
                    {showPassword ? (
                        <Image
                            src={HidePasswordIcon}
                            width={20}
                            height={20}
                            alt="Hide password"
                        />
                    ) : (
                        <Image
                            src={ShowPasswordIcon}
                            width={20}
                            height={20}
                            alt="Show password"
                        />
                    )}
                </div>
                <div className={styles.input_error}>
                    {errors[input.name] && (
                        <Tooltip
                            label={errors[input.name].message}
                            fontSize="sm"
                            bg="red.600"
                            textAlign="center"
                            isOpen={isOpen}
                            placement="auto"
                        >
                            <Image
                                src={ErrorIcon}
                                width={20}
                                height={20}
                                alt="Error"
                                onMouseEnter={onOpen}
                                onMouseLeave={onClose}
                                onClick={onToggle}
                            />
                        </Tooltip>
                    )}
                </div>
            </div>
        </Form.Group>
    );
};

export default FormPasswordInput;
