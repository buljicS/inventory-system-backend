import { Form } from "react-bootstrap";
import styles from "./FormInput.module.scss";
import Image from "next/image";
import { Tooltip, useDisclosure } from "@chakra-ui/react";
import { ErrorIcon } from "@/resources/icons";
const FormTextInput = ({ input, errors, register }) => {
    const { isOpen, onOpen, onToggle, onClose } = useDisclosure();

    return (
        <Form.Group
            className="mb-3"
            controlId={input.name + input.id}
            key={input.id}
        >
            <Form.Label>{input.label}</Form.Label>
            <div className={styles.input}>
                <Form.Control
                    type={input.type}
                    placeholder={input.placeholder}
                    // @ts-ignore */
                    {...register(input.name)}
                    maxLength={50}
                />

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

export default FormTextInput;
