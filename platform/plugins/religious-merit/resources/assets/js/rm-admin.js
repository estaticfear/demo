import AddProductModal from './components/AddProductModal';
import { createRoot } from 'react-dom/client';

const addProductModal = document.getElementById('add-product-modal');
if (addProductModal) {
    const projectId = addProductModal.dataset.projectid;
    const meritId = addProductModal.dataset.meritid;
    const root = createRoot(addProductModal); // createRoot(container!) if you use TypeScript
    root.render(<AddProductModal projectId={projectId} meritId={meritId} />);
}
