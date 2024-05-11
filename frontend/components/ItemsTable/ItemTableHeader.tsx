import { PlusIconSecond, DeleteIcon } from "@/resources/icons";
import { ButtonIcon } from "@/components";
import { useDisclosure, Button } from "@chakra-ui/react";
import { InputText } from "primereact/inputtext";
import { Form } from "react-bootstrap";
import AddItemModal from "./AddItemModal";
import DeleteItemModal from "./DeleteItemModal";

const ItemTableHeader = ({
    globalFilterValue,
    onGlobalFilterChange,
    selectedItems,
}) => {
    const {
        isOpen: addItemIsOpen,
        onOpen: onAddItemOpen,
        onClose: onAddItemClose,
    } = useDisclosure();
    const {
        isOpen: deleteItemIsOpen,
        onOpen: onDeleteItemOpen,
        onClose: onDeleteItemClose,
    } = useDisclosure();

    return (
        <div className="items_header">
            <div className="items_header_top">
                <Form.Select>
                    <option>Room 356</option>
                    <option>Room 123</option>
                    <option>Room 765</option>
                </Form.Select>
            </div>
            <div className="items_header_bottom">
                <div className="items_header_bottom_actions">
                    <Button
                        colorScheme="green"
                        onClick={onAddItemOpen}
                        leftIcon={<ButtonIcon icon={PlusIconSecond} />}
                    >
                        Add an item
                    </Button>
                    <Button
                        colorScheme="red"
                        onClick={onDeleteItemOpen}
                        leftIcon={<ButtonIcon icon={DeleteIcon} />}
                        isDisabled={
                            !selectedItems || selectedItems.length === 0
                        }
                    >
                        Delete selected items
                    </Button>
                </div>
                <div className="items_header_bottom_search">
                    <InputText
                        value={globalFilterValue}
                        onChange={onGlobalFilterChange}
                        placeholder="Search"
                    />
                </div>
            </div>
            <AddItemModal
                addItemIsOpen={addItemIsOpen}
                onAddItemClose={onAddItemClose}
            />
            <DeleteItemModal
                deleteItemIsOpen={deleteItemIsOpen}
                onDeleteItemClose={onDeleteItemClose}
            />
        </div>
    );
};

export default ItemTableHeader;
