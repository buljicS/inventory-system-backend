import {
    Modal,
    ModalOverlay,
    ModalContent,
    ModalHeader,
    ModalBody,
    ModalCloseButton,
    Button,
} from "@chakra-ui/react";
import { Form } from "react-bootstrap";
import { ADD_ROOM_FORM_INPUTS } from "@/utils/constants";
import { FormInput } from "@/components";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { TRoomsData } from "@/utils/types";
import { ADD_ROOMS_SCHEMA } from "@/utils/constants";
import "./RoomTable.scss";

const AddRoomModal = ({ addRoomIsOpen, onAddRoomClose }) => {
    const {
        register,
        handleSubmit,
        formState: { errors },
    } = useForm<TRoomsData>({ resolver: zodResolver(ADD_ROOMS_SCHEMA) });

    const onSubmit = () => {};

    return (
        <Modal isOpen={addRoomIsOpen} onClose={onAddRoomClose}>
            <ModalOverlay />
            <ModalContent>
                <ModalHeader> Add a room</ModalHeader>
                <ModalCloseButton />
                <ModalBody>
                    <Form onSubmit={handleSubmit(onSubmit)}>
                        {ADD_ROOM_FORM_INPUTS.map((input) => (
                            <FormInput
                                input={input}
                                register={register}
                                errors={errors}
                                key={input.id}
                            />
                        ))}
                        <div className="form_buttons">
                            <Button
                                colorScheme="blue"
                                mr={3}
                                onClick={onAddRoomClose}
                            >
                                Close
                            </Button>
                            <Button colorScheme="green" type="submit">
                                Add
                            </Button>
                        </div>
                    </Form>
                </ModalBody>
            </ModalContent>
        </Modal>
    );
};

export default AddRoomModal;
