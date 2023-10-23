import axios from 'axios';
import { useEffect, useState } from 'react';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';
import './style.css';

function AddProductModal({ meritId }) {
    const [show, setShow] = useState(false);
    const [project, setProject] = useState(null);
    const [merit, setMerit] = useState(null);
    const [products, setProducts] = useState(null);
    const [html, setHtml] = useState(null);
    const [isLoading, setIsLoading] = useState(false);
    const [productType, setProductType] = useState(null);

    function renderProductsTable(productType) {
        let table = `
        <div class="table-responsive project-tabs project-table-modal">
        <table class="table">
            <thead class="sticky-top">
                <tr>
                    <th>Mô tả</th>
                    <th class="text-end">Số lượng cần</th>
                    <th class="text-end">Tổng số lượng đã đóng góp</th>
                    <th class="text-end">Số lượng</th>
                </tr>
            </thead>
        `;
        let tbody = '<tbody class="table-light">';
        products?.map((p) => {
            if (p.product_type === productType) {
                let name = p.product_name;
                if (p.variation_attributes) {
                    name += ` ${p.variation_attributes}`;
                }
                tbody += `
                <tr class="align-middle product-row" data-product-name="${p.product_name}">
                    <td>${name}</td>
                    <td class="text-end">${p.project_qty}</td>
                    <td class="text-end">${p.total_merited_qty}</td>
                    <td class="text-end d-flex justify-content-end">
                        <input type="hidden" name="merit_project_product_id" value="${p.merit_project_product_id}">
                        <input type="hidden" name="name" value="${name}">
                        <input type="hidden" name="quantityOrigin" value="${p.project_qty - p.total_merited_qty}}">
                        <input type="text" class="form-control text-end" name="quantity" value="${
                            p.merit_qty
                        }" placeholder="0" style="width: 140px"
                    </td>
                </tr>
                `;
            }
        });
        tbody += '</tbody>';
        table += tbody;
        table += '</table></div>';
        setHtml(table);
    }

    const handleClose = () => {
        setHtml(null);
        setShow(false);
        setProductType(null);
    };
    const handleShow = (productType) => {
        setShow(true);
        setProductType(productType);
        renderProductsTable(productType);
    };

    const onSubmit = () => {
        setIsLoading(true);
        const rows = document.querySelectorAll('.product-row');
        const productData = [];

        rows.forEach((element) => {
            const quantity = parseInt($(element).find('input[name="quantity"]').val());

            if (quantity > 0) {
                const obj = {
                    merit_project_product_id: $(element).find('input[name="merit_project_product_id"]').val(),
                    name: $(element).find('input[name="name"]').val(),
                    quantity: quantity,
                };
                productData.push(obj);
            }
        });
        axios
            .post(`/admin/religious-merits/update-merit-products/${meritId}/${productType}`, {
                merit_products: productData,
            })
            .then((res) => {
                window.location.reload();
            })
            .catch((res) => {
                Cmat.handleError(res.response.data);
            });
    };

    function getMeritDetail(meritId) {
        axios
            .get('/admin/religious-merits/get-detail/' + meritId)
            .then((res) => {
                let data = res.data.data;
                setMerit(data);
                setProject(data.project);
                const projectProducts = (data.project.digital_products || []).concat(
                    data.project.physical_products || [],
                );
                const products = new Map();
                data.merit_products?.map((item) => {
                    products.set(item.merit_project_product_id, {
                        merit_project_product_id: item.merit_project_product_id,
                        product_name: item.product_name,
                        merit_qty: item.qty,
                        product_type: item.product_type,
                    });
                });
                projectProducts.map((projectProduct) => {
                    const product = products.get(projectProduct.id);
                    if (product) {
                        products.set(projectProduct.id, {
                            ...product,
                            variation_attributes: projectProduct.product?.variation_attributes,
                            total_merited_qty: projectProduct.total_merit_qty,
                            project_qty: projectProduct.qty,
                            is_not_allow_merit_a_part: !!projectProduct.is_not_allow_merit_a_part,
                        });
                    } else {
                        products.set(projectProduct.id, {
                            merit_project_product_id: projectProduct.id,
                            product_name: projectProduct.product_name,
                            merit_qty: 0,
                            product_type: projectProduct.product_type,
                            variation_attributes: projectProduct.product?.variation_attributes,
                            total_merited_qty: projectProduct.total_merit_qty,
                            project_qty: projectProduct.qty,
                            is_not_allow_merit_a_part: !!projectProduct.is_not_allow_merit_a_part,
                        });
                    }
                });
                setProducts(Array.from(products.values()));
            })
            .catch((res) => {
                console.log('res', res);
            });
    }

    useEffect(() => {
        if (meritId && !merit) {
            // get merit detail
            getMeritDetail(meritId);
        }
    }, []);

    return project ? (
        <>
            {project.can_contribute_artifact ? (
                <Button className="me-2" variant="primary" onClick={() => handleShow('physical')}>
                    Thêm hiện vật
                </Button>
            ) : null}

            {project.can_contribute_effort ? (
                <Button variant="primary" onClick={() => handleShow('digital')}>
                    Thêm công sức
                </Button>
            ) : null}

            <Modal show={show} onHide={handleClose}>
                <Modal.Header closeButton>
                    <div style={{ fontWeight: 'bold', fontSize: '20px' }} className="p-2 text-white">
                        Danh sách hiện vật/công sức
                    </div>
                </Modal.Header>
                <Modal.Body>
                    <div dangerouslySetInnerHTML={{ __html: html }}></div>
                </Modal.Body>
                <Modal.Footer>
                    <Button variant="secondary" onClick={handleClose}>
                        Close
                    </Button>
                    <Button disabled={isLoading} variant="primary" onClick={onSubmit}>
                        {isLoading ? 'Loading…' : 'Cập nhật'}
                    </Button>
                </Modal.Footer>
            </Modal>
        </>
    ) : null;
}

export default AddProductModal;
