import "primeicons/primeicons.css";
import { InputText } from "primereact/inputtext";
import { useDisclosure, Button } from "@chakra-ui/react";
import "primereact/resources/themes/bootstrap4-light-blue/theme.css";
import AddRoomModal from "./AddRoomModal";
import DeleteRoomsModal from "./DeleteRoomsModal";
import { PlusIconSecond, DeleteIcon } from "@/resources/icons";
import { ButtonIcon } from "@/components";

const RoomTableHeader = ({
    globalFilterValue,
    onGlobalFilterChange,
    selectedRooms,
}) => {
    const {
        isOpen: addRoomIsOpen,
        onOpen: onAddRoomOpen,
        onClose: onAddRoomClose,
    } = useDisclosure();
    const {
        isOpen: deleteRoomIsOpen,
        onOpen: onDeleteRoomOpen,
        onClose: onDeleteRoomClose,
    } = useDisclosure();

    return (
        <div className="rooms-header">
            <div className="rooms-actions">
                <Button
                    colorScheme="green"
                    onClick={onAddRoomOpen}
                    leftIcon={<ButtonIcon icon={PlusIconSecond} />}
                >
                    Add a room
                </Button>
                <Button
                    colorScheme="red"
                    onClick={onDeleteRoomOpen}
                    leftIcon={<ButtonIcon icon={DeleteIcon} />}
                    isDisabled={!selectedRooms || selectedRooms.length === 0}
                >
                    Delete selected rooms
                </Button>
            </div>
            <div className="rooms-search">
                <InputText
                    value={globalFilterValue}
                    onChange={onGlobalFilterChange}
                    placeholder="Search"
                />
            </div>
            <AddRoomModal
                addRoomIsOpen={addRoomIsOpen}
                onAddRoomClose={onAddRoomClose}
            />
            <DeleteRoomsModal
                deleteRoomIsOpen={deleteRoomIsOpen}
                onDeleteRoomClose={onDeleteRoomClose}
            />
        </div>
    );
};

export default RoomTableHeader;
